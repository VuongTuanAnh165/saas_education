# Database Schema (Sprint 0)

## Bảng `users`

*Mô tả: Lưu trữ tất cả tài khoản người dùng (admins, teachers, students)*

| STT | Cột | Kiểu dữ liệu | Mô tả |
|---|---|---|---|
| 1 | `id` | `bigint unsigned` | Khóa chính |
| 2 | `name` | `varchar(255)` | Tên người dùng |
| 3 | `email` | `varchar(255)` | Email đăng nhập (unique) |
| 4 | `email_verified_at` | `timestamp nullable` | Thời điểm xác thực email |
| 5 | `password` | `varchar(255)` | Mật khẩu đã hash |
| 6 | `role` | `varchar(32)` | Vai trò người dùng: `admin`, `teacher`, `student` |
| 7 | `status` | `varchar(32)` | Trạng thái người dùng: `active`, `suspended` |
| 8 | `password_setup_token` | `varchar(64) nullable` | Token để thiết lập mật khẩu lần đầu |
| 9 | `password_setup_token_expires_at` | `timestamp nullable` | Thời gian hết hạn của token thiết lập mật khẩu |
| 10 | `remember_token` | `varchar(100) nullable` | Token "ghi nhớ đăng nhập" |
| 11 | `created_at` / `updated_at` | `timestamp` | Thời gian tạo/cập nhật |

---

## Bảng `teachers`

*Mô tả: Lưu trữ thông tin giáo viên (tenant)*

| STT | Cột | Kiểu dữ liệu | Mô tả |
|---|---|---|---|
| 1 | `id` | `bigint unsigned` | Khóa chính |
| 2 | `user_id` | `bigint unsigned` | Khóa ngoại tới `users.id` |
| 3 | `display_name` | `varchar(255)` | Tên hiển thị công khai của giáo viên |
| 4 | `status` | `varchar(32)` | Trạng thái tenant của giáo viên: `active`, `suspended` |
| 5 | `created_at` / `updated_at` | `timestamp` | Thời gian tạo/cập nhật |

---

## Bảng `students`

*Mô tả: Lưu trữ thông tin học sinh, liên kết với một giáo viên (tenant)*

| STT | Cột | Kiểu dữ liệu | Mô tả |
|---|---|---|---|
| 1 | `id` | `bigint unsigned` | Khóa chính |
| 2 | `user_id` | `bigint unsigned` | Khóa ngoại tới `users.id` |
| 3 | `teacher_id` | `bigint unsigned` | Khóa ngoại tới `teachers.id` (tenant mà học sinh thuộc về) |
| 4 | `full_name` | `varchar(255)` | Họ và tên đầy đủ của học sinh |
| 5 | `created_at` / `updated_at` | `timestamp` | Thời gian tạo/cập nhật |

---

## Bảng `audit_logs`

*Mô tả: Lưu trữ lịch sử các hành động quan trọng trong hệ thống*

| STT | Cột | Kiểu dữ liệu | Mô tả |
|---|---|---|---|
| 1 | `id` | `bigint unsigned` | Khóa chính |
| 2 | `actor_user_id` | `bigint unsigned` | Người dùng thực hiện hành động |
| 3 | `teacher_id` | `bigint unsigned nullable` | Ngữ cảnh tenant của hành động |
| 4 | `action` | `varchar(255)` | Mã hành động (ví dụ: `AUTH_LOGIN`) |
| 5 | `target_type` | `varchar(255) nullable` | Lớp của đối tượng bị tác động (polymorphic) |
| 6 | `target_id` | `bigint unsigned nullable` | ID của đối tượng bị tác động (polymorphic) |
| 7 | `metadata` | `json nullable` | Dữ liệu bổ sung cho hành động |
| 8 | `created_at` / `updated_at` | `timestamp` | Thời gian tạo/cập nhật |

---

## Bảng `personal_access_tokens`

*Mô tả: Lưu trữ Sanctum personal access tokens để xác thực API*

| STT | Cột | Kiểu dữ liệu | Mô tả |
|---|---|---|---|
| 1 | `id` | `bigint unsigned` | Khóa chính |
| 2 | `tokenable_type` | `varchar(255)` | Lớp của chủ sở hữu token (polymorphic) |
| 3 | `tokenable_id` | `bigint unsigned` | ID của chủ sở hữu token (polymorphic) |
| 4 | `name` | `varchar(255)` | Tên của token |
| 5 | `token` | `varchar(64)` | Chuỗi token (unique) |
| 6 | `abilities` | `text nullable` | Các quyền của token |
| 7 | `last_used_at` | `timestamp nullable` | Thời điểm cuối cùng sử dụng token |
| 8 | `expires_at` | `timestamp nullable` | Thời gian hết hạn của token |
| 9 | `created_at` / `updated_at` | `timestamp` | Thời gian tạo/cập nhật |
