# Laravel 12 Coding Rules & Guidelines
Chuẩn hoá TOÀN DIỆN – Senior / Lead / Production Level

Tài liệu này là **bộ luật code chính thức** cho các dự án Laravel 12 dùng lâu dài (3–5 năm), team nhiều dev, nhiều module, yêu cầu cao về chất lượng – bảo trì – mở rộng – hiệu năng – bảo mật.

**Mục tiêu tối thượng**
- Bất kỳ dev nào vào project cũng code ra kết quả giống nhau về **cấu trúc**, **tư duy**, **phong cách**.
- Rule phải **enforceable** khi review PR và khi chạy CI.

**Nguyên tắc áp dụng**
- Nếu rule xung đột với yêu cầu sản phẩm, Tech Lead ra quyết định và ghi rõ trong PR.
- Mọi ngoại lệ phải có lý do và phạm vi ảnh hưởng.

---

## 0. Must / Should / Must Not
- **MUST**: bắt buộc, vi phạm là reject PR.
- **SHOULD**: khuyến nghị mạnh, vi phạm phải có lý do.
- **MUST NOT**: cấm tuyệt đối.

---

## 1. Triết lý kiến trúc & nguyên tắc gốc

### 1.1 SOLID (MUST)
- **Single Responsibility**: 1 class chỉ làm 1 việc.
- **Open/Closed**: mở rộng bằng class mới, hạn chế sửa class production.
- **Liskov Substitution**: class con thay được class cha.
- **Interface Segregation**: interface nhỏ, đúng nhu cầu.
- **Dependency Inversion**: phụ thuộc abstraction.

### 1.2 DRY – KISS – YAGNI (MUST)
- **DRY**: không copy–paste logic, trích xuất `Service`/`Action`/`Repository`.
- **KISS**: ưu tiên giải pháp đơn giản.
- **YAGNI**: không “đoán tương lai” để code dư thừa.

### 1.3 Clean Code Mindset (MUST)
- Code đọc như văn nói.
- Rõ ràng hơn thông minh.
- Naming phản ánh domain.

---

## 2. Naming Convention (MUST)

### 2.1 Class
| Loại | Quy ước | Ví dụ |
| --- | --- | --- |
| Controller | `PascalCase` + `Controller` | `UserController` |
| Model | `PascalCase` (số ít) | `OrderItem` |
| Service | `PascalCase` + `Service` | `PaymentService` |
| Action | `PascalCase` + `Action` | `CreateOrderAction` |
| Repository | `PascalCase` + `Repository` | `UserRepository` |
| Request | `PascalCase` + `Request` | `UpdateProfileRequest` |
| Resource | `PascalCase` + `Resource` | `UserResource` |
| Event | `PascalCase` | `OrderCreated` |
| Listener | `PascalCase` | `SendOrderMail` |
| Job | `PascalCase` | `SyncInventoryJob` |
| Policy | `PascalCase` | `OrderPolicy` |
| Rule | `PascalCase` | `StrongPassword` |
| Enum | `PascalCase` | `UserStatus` |
| Exception | `PascalCase` + `Exception` | `AuthorizationException` |

### 2.2 Method / Variable
- `camelCase`.
- Boolean bắt đầu bằng `is` / `has` / `can`.

### 2.3 Constant
- `SCREAMING_SNAKE_CASE`.

### 2.4 API Code Convention (MUST)
- **MUST** dùng `SCREAMING_SNAKE_CASE` cho `code` trong API response.
- **MUST** namespace hoá `code` cho success (ví dụ: `AUTH_LOGIN_SUCCESS`, `ADMIN_TEACHER_CREATED`, `TEACHER_STUDENT_CREATED`).
- **MUST** dùng `SCREAMING_SNAKE_CASE` cho error codes (ví dụ: `VALIDATION_ERROR`, `UNAUTHENTICATED`, `TEACHER_SUSPENDED`).

---

## 3. Code Style & Quality Gates (MUST)

### 3.1 Formatting
- **MUST** chạy formatter thống nhất (khuyến nghị: Laravel Pint) và không tranh luận style trong PR.
- **MUST NOT** commit code không format.

### 3.2 Strictness
- **MUST** bật `declare(strict_types=1);` cho các class/file thuần PHP (không bắt buộc cho file Laravel auto-generated nếu team chưa chuẩn hoá).
- **SHOULD** type-hint đầy đủ: param/return types, property types.

### 3.3 Static analysis
- **SHOULD** dùng PHPStan/Psalm mức phù hợp (tối thiểu phát hiện sai type rõ ràng).

---

## 4. Cấu trúc thư mục (MUST)

```
app/
 ├── Actions/        # Logic nhỏ, 1 nhiệm vụ
 ├── Console/        # Command
 ├── Enums/          # PHP Enum
 ├── Exceptions/     # Custom exception
 ├── Helpers/        # Helper thuần, không state
 ├── Http/
 │   ├── Controllers/
 │   ├── Middleware/
 │   ├── Requests/
 │   └── Resources/
 ├── Jobs/
 ├── Models/
 ├── Observers/
 ├── Policies/
 ├── Providers/
 ├── Repositories/
 ├── Rules/
 ├── Services/
 └── Traits/
```

- **MUST NOT** đặt business logic trong `Controller`, `Model`, `View`.

---

## 5. Controller Rules (Thuần điều phối) (MUST)

### 5.1 Trách nhiệm
Controller chỉ làm:
1. Nhận request
2. Authorize/Policy (nếu cần)
3. Gọi `Service` (hoặc `Action` nếu cực nhỏ)
4. Trả `Resource` / `Response`
5. Khai báo middleware

### 5.2 Quy định
- **MUST**: method ngắn (khuyến nghị <= 30 dòng).
- **MUST NOT**: gọi trực tiếp Model.
- **MUST NOT**: viết query.
- **MUST NOT**: `try/catch` (để global handler).
- **MUST NOT**: map/format dữ liệu phức tạp (để `Resource`/`DTO`).

### 5.3 Dependency Injection
- **MUST** inject qua constructor.
- **MUST NOT** dùng `app()` trong Controller.

---

## 6. Service Layer (Business logic duy nhất) (MUST)

Service là nơi **DUY NHẤT** được phép chứa **business logic**.

### 6.1 Business logic là gì?
Business logic là:
- Quy tắc nghiệp vụ gắn với quyết định, trạng thái, flow
- Quy trình nhiều bước

Không phải business logic:
- Validate request (`FormRequest`)
- Format JSON (`Resource`)
- Map request -> array
- Query đơn thuần

### 6.2 Service ĐƯỢC làm gì
- Điều phối nghiệp vụ
- Gọi nhiều `Repository`/`Action`
- Quyết định “được/không được”
- `DB::transaction(...)`
- Dispatch `Event` / `Job`
- Throw `BusinessException`

### 6.3 Service KHÔNG ĐƯỢC làm gì (MUST NOT)
- Nhận `Request` / trả `Response`
- Truy cập Session
- Gọi trực tiếp `Auth::user()`
  - **MUST** truyền `User $actor` (hoặc `ActorContext`) từ Controller vào Service.
- Đọc `env()` trực tiếp
  - **MUST** dùng `config()` và inject config nếu cần.

### 6.4 Thiết kế Service chuẩn
- **MUST**: 1 service = 1 ngữ cảnh.
- **MUST NOT**: God service.
- **MUST**: public method đặt tên theo hành động nghiệp vụ (`approveOrder()`, `changePassword()`).

### 6.5 Transaction
- **MUST**: transaction chỉ nằm trong Service.
- **MUST NOT**: transaction trong Controller/Repository/Model.

### 6.6 Service gọi Service
- **SHOULD**: chỉ gọi khi khác ngữ cảnh.
- **MUST NOT**: vòng phụ thuộc.

### 6.7 Error handling
- **MUST**: lỗi nghiệp vụ throw `BusinessException` (không return `false/null`).
- **MUST**: lỗi hệ thống throw `SystemException` hoặc để exception tự bubble lên handler.

### 6.8 Giới hạn
- **SHOULD**: service <= 300 dòng; vượt phải tách domain/action.

---

## 7. Action Layer (Atomic logic)

### 7.1 Định nghĩa
Action là logic nhỏ, nguyên tử, có thể tái sử dụng.

### 7.2 Khi dùng
- Logic thuần, dễ test độc lập
- Là 1 bước trong Service

### 7.3 Khi không dùng
- Logic quyết định flow nghiệp vụ lớn
- Cần transaction

### 7.4 Action có được gọi DB không?
Rule để **nhất quán** (tránh mâu thuẫn):
- **MUST (mặc định)**: Action **không** query DB.
- **SHOULD**: Action nhận dữ liệu đã chuẩn bị từ Service.
- **Ngoại lệ (SHOULD, có lý do)**: Action được phép ghi DB khi nó là thao tác atomic kiểu “write một entity” và **không** chứa business rule. Khi dùng ngoại lệ, Tech Lead phải approve.

---

## 8. Repository Pattern (Query discipline)

### 8.1 Repository là gì?
Repository là lớp chịu trách nhiệm **query data** (đọc/ghi DB) theo kỷ luật.

### 8.2 Khi bắt buộc dùng
- Query phức tạp
- Query dùng nhiều nơi
- Cần mock dễ trong test

### 8.3 Khi không dùng
- CRUD rất đơn giản, chỉ dùng 1 nơi

### 8.4 Chuẩn Repository
- **MUST** return `Model` / `Collection` / `Paginator` (không return array tuỳ tiện).
- **MUST NOT** chứa business rule.
- **MUST NOT** mở transaction.

---

## 9. Model Discipline (MUST)

### 9.1 Model được phép chứa
- Relationship
- Cast
- Accessor/Mutator (format)
- Scope đơn giản

### 9.2 Model bị cấm
- Business decision
- Gọi Service
- Transaction
- Gửi mail / gọi API

---

## 10. DTO / Data Mapping (SHOULD)

- **SHOULD** dùng DTO cho boundary giữa Controller <-> Service khi data phức tạp.
- **MUST NOT** truyền `Request` object vào Service.
- **SHOULD** chuẩn hoá input bằng `FormRequest::validated()` hoặc DTO.

---

## 11. Exception Strategy & API Error Contract (MUST)

### 11.1 Phân loại
- `BusinessException`: lỗi nghiệp vụ, client xử lý được (4xx).
- `SystemException`: lỗi hệ thống, client không xử lý được (500).

### 11.2 Mapping HTTP
| Exception | HTTP |
| --- | --- |
| Validation | 422 |
| BusinessException | 400 / 422 |
| Authentication | 401 |
| AuthorizationException | 403 |
| ModelNotFound | 404 |
| SystemException | 500 |

### 11.3 Không làm vỡ API
- **MUST** response lỗi format ổn định.
- **MUST NOT** leak stacktrace ra client.

---

## 11.4 API Response Contract (MUST)

### 11.4.1 Response envelope
- **MUST** mọi API trả về cùng một envelope để frontend có thể xử lý thống nhất.
- **MUST** trả response qua `App\Http\Responses\ApiResponse`.
- **MUST** render envelope bằng `App\Http\Resources\ApiResponseResource`.

Success:

```json
{
  "success": true,
  "code": "AUTH_LOGIN_SUCCESS",
  "message": "Đăng nhập thành công.",
  "data": {},
  "meta": {
    "request_id": "uuid",
    "timestamp": "2026-01-17T00:00:00Z"
  }
}
```

Error:

```json
{
  "success": false,
  "code": "FORBIDDEN",
  "message": "Bạn không có quyền truy cập.",
  "data": {},
  "meta": {
    "request_id": "uuid",
    "timestamp": "2026-01-17T00:00:00Z"
  }
}
```

### 11.4.2 `data` must be object
- **MUST** `data` luôn là object.
- **MUST NOT** trả `data: null`.
- Với response không có payload, `data` phải là `{}`.

### 11.4.3 Validation error
- **MUST** dùng `code=VALIDATION_ERROR` và trả chi tiết lỗi ở `data.errors`.

### 11.4.4 Request ID
- **MUST** hỗ trợ trace thông qua `X-Request-Id`.
- Nếu client không gửi `X-Request-Id`, server **MUST** tự sinh request id.
- Response **MUST** trả lại header `X-Request-Id`.

### 11.4.5 i18n message
- **MUST** hỗ trợ đa ngôn ngữ thông qua header `Accept-Language`.
- **MUST** hỗ trợ tối thiểu `vi`, `en`.
- **MUST** trả `Content-Language` trong response.

### 11.4.6 Translation key convention
- **MUST** translation keys là `snake_case` theo convention Laravel.
- **MUST** map translation key từ `code` theo quy tắc: `snake_case(code)`.
  - Ví dụ: `TEACHER_SUSPENDED` -> `teacher_suspended`
  - Ví dụ: `AUTH_LOGIN_SUCCESS` -> `auth_login_success`

---

## 11.5 HTTP Status Code Convention (MUST)

- **MUST NOT** hard-code số status code như `401`, `403`.
- **MUST** dùng `Symfony\Component\HttpFoundation\Response::HTTP_*`.

---

## 11.6 API Documentation (Scramble) (MUST)

- **MUST** dùng `dedoc/scramble` để generate OpenAPI.
- **MUST** đảm bảo `/docs/api` hiển thị đầy đủ schema `data`.
- **MUST** dùng `JsonResource` (ví dụ `*DataResource`) cho mọi response có payload để Scramble suy ra schema.
- Với response rỗng, **MUST** dùng `EmptyDataResource` để `data` vẫn là object.

---

## 12. Validation Rules (MUST)

- **MUST**: FormRequest theo use-case (`CreateUserRequest` khác `UpdateUserRequest`).
- **SHOULD**: Rule phức tạp -> Custom Rule class.
- **SHOULD**: Validate theo ngữ cảnh (route/role/state).

---

## 13. Authorization (Policy/Gate) (MUST)

- **MUST** dùng Policy cho quyền trên resource.
- **SHOULD** authorize ở Controller (hoặc middleware) trước khi gọi Service.
- **MUST NOT** “rải” check quyền lặt vặt trong repository/model.

---

## 14. Events, Jobs, Queue (MUST/SHOULD)

- **MUST**: dispatch event/job trong Service sau khi nghiệp vụ thành công.
- **SHOULD**: tác vụ nặng/IO (email, gọi API, export, sync) đưa vào Job.
- **MUST**: Job phải idempotent (chạy lại không tạo sai dữ liệu).
- **SHOULD**: dùng retry/backoff hợp lý, và dead-letter/failed jobs theo chuẩn project.

---

## 15. Logging, Auditing, Observability (SHOULD)

- **SHOULD** log theo ngữ cảnh nghiệp vụ (order_id, user_id, request_id).
- **MUST NOT** log dữ liệu nhạy cảm: password, token, OTP, full card.
- **SHOULD** thêm audit log cho hành động quan trọng (approve/refund/change role).

---

## 16. Database & Migration Rules (MUST)

- **MUST** migration có rollback được.
- **MUST** tạo index cho các cột filter/sort/join quan trọng.
- **MUST NOT** làm migration “nặng” trên bảng lớn trong giờ cao điểm nếu không có plan.
- **SHOULD** dùng soft delete khi domain yêu cầu audit/khôi phục.

---

## 17. Security Rules (MUST)

- **MUST** không dùng `env()` ngoài config.
- **MUST** validate input và authorize đầy đủ.
- **MUST** chống mass assignment (`$fillable`/`$guarded` rõ ràng).
- **MUST** sanitize output phù hợp khi render view.
- **MUST NOT** hardcode secret/API key.

---

## 18. Performance & Query Rules (SHOULD)

- **SHOULD** tránh N+1 (`with()`, eager load hợp lý).
- **SHOULD** paginate list endpoints.
- **SHOULD** cache các lookup data ổn định.
- **MUST NOT** load collection lớn vào memory nếu có thể stream/chunk.

---

## 19. Testing Strategy (MUST)

### 19.1 Test gì
- **MUST** test:
  - Service
  - Action
  - Policy

### 19.2 Mock đúng chỗ
- **MUST** mock:
  - Repository khi unit test Service
  - External API clients
- **MUST NOT** mock thứ đang test.

### 19.3 Regression
- **MUST**: mỗi bug fix phải có test.

---

## 20. PR Checklist (Tech Lead – 5 phút) (MUST)

### Kiến trúc
- Controller mỏng, không business logic?
- Service đúng ngữ cảnh, không God service?
- Model sạch?

### Data & Error
- Không truyền `Request` vào Service?
- Exception mapping hợp lý, không leak?

### Security
- Authorization đúng nơi?
- Không log dữ liệu nhạy cảm?

### Test
- Có test mới cho feature/bug?
- Test có ý nghĩa (assert đúng business rule)?
