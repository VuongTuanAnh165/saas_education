# Nuxt Coding Rules & Guidelines
Chuẩn hoá TOÀN DIỆN – Senior / Lead / Production Level

Tài liệu này là **bộ luật code chính thức** cho các dự án Nuxt (Nuxt 3+) dùng lâu dài, team nhiều dev, yêu cầu cao về chất lượng – bảo trì – mở rộng – hiệu năng – bảo mật.

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

## 1. Triết lý & Nguyên tắc gốc

- **Single Responsibility (MUST)**: 1 component/composable chỉ làm 1 việc.
- **DRY (Don't Repeat Yourself) (MUST)**: không copy-paste logic, trích xuất `Composable` / `Component` / `Util`.
- **KISS (Keep It Simple, Stupid) (MUST)**: ưu tiên giải pháp đơn giản, dễ hiểu.
- **Composition over Inheritance (MUST)**: ưu tiên dùng Composition API (composables) thay vì mixins hoặc các pattern kế thừa phức tạp.

---

## 2. Code Style & Quality Gates (MUST)

- **Formatting (MUST)**: dùng Prettier và ESLint theo config của project. **MUST NOT** commit code không format.
- **Linting (MUST)**: tuân thủ rule ESLint (khuyến nghị: `@nuxtjs/eslint-config-typescript`).
- **TypeScript (MUST)**: toàn bộ codebase phải dùng TypeScript. **MUST** bật `strict: true` trong `tsconfig.json`.
- **Static Analysis (SHOULD)**: dùng Volar/Vue TSC để check type error trong template.

---

## 3. Naming Convention (MUST)

| Loại | Quy ước | Ví dụ |
| --- | --- | --- |
| Component | `PascalCase` | `UserProfileCard.vue` |
| Page | `kebab-case` | `user-profile.vue` |
| Layout | `kebab-case` | `default.vue`, `auth.vue` |
| Composable | `camelCase`, bắt đầu bằng `use` | `useAuth`, `useApiFetch` |
| Pinia Store | `camelCase` + `Store` | `authStore`, `cartStore` |
| Util/Helper | `camelCase` | `formatDate.ts` |
| API Route | `kebab-case` | `users.get.ts`, `auth/login.post.ts` |
| Variable/Function | `camelCase` | `const userProfile = ...` |
| Constant | `SCREAMING_SNAKE_CASE` | `const API_TIMEOUT = 5000;` |
| Type/Interface | `PascalCase` | `interface UserProfile { ... }` |

---

## 4. Cấu trúc thư mục (MUST)

Tuân thủ cấu trúc mặc định của Nuxt và mở rộng theo feature.

```
client/
 ├── assets/              # Font, icon, global CSS
 ├── components/          # Component tái sử dụng
 │   ├── global/          # Component đăng ký global (BaseButton.vue)
 │   └── features/        # Component theo từng feature (UserProfile/)
 ├── composables/         # Logic tái sử dụng (useAuth.ts)
 ├── layouts/             # Layout chung (default.vue)
 ├── middleware/          # Route middleware (auth.global.ts)
 ├── pages/               # File-based routing
 ├── plugins/             # Plugin (sentry.client.ts)
 ├── public/              # File tĩnh (favicon.ico)
 ├── server/              # Server engine (Nitro)
 │   ├── api/             # API routes (auth/login.post.ts)
 │   ├── middleware/      # Server middleware
 │   └── utils/           # Util phía server
 ├── store/               # Pinia stores (auth.ts)
 ├── types/               # Global type definitions (index.d.ts)
 └── utils/               # Util phía client (formatters, validators)
```

- **Feature-based structure (SHOULD)**: trong `components`, `composables`, `store`, nên nhóm file theo feature để dễ quản lý.

---

## 5. Component Design (MUST)

- **`<script setup>` (MUST)**: dùng `<script setup lang="ts">` cho tất cả component.
- **Single Responsibility (MUST)**: component không quá 200 dòng (không tính style). Component lớn phải được tách thành các component con.
- **Props & Emits (MUST)**:
  - Dùng `defineProps` và `defineEmits` với type-based declaration.
  - Prop **MUST** có type rõ ràng.
  - Prop **SHOULD** có `default` value nếu không `required`.
- **Smart vs Dumb Components (SHOULD)**:
  - **Smart (Container)**: component ở level page, chịu trách nhiệm fetch data, gọi store action.
  - **Dumb (Presentational)**: component con, chỉ nhận props và emit event, không tự fetch data hay gọi store.
- **Scoped Styles (MUST)**: dùng `<style scoped>` để tránh xung đột CSS.
- **`v-if` vs `v-show` (MUST)**:
  - `v-if`: dùng khi element ít khi thay đổi trạng thái (chi phí toggle cao).
  - `v-show`: dùng khi element thay đổi trạng thái thường xuyên (chi phí render ban đầu cao).

---

## 6. State Management (Pinia) (MUST)

- **When to use (SHOULD)**: dùng store cho state global hoặc state cần chia sẻ giữa các page/component không liên quan trực tiếp.
- **Structure (MUST)**:
  - **State**: là một function trả về object.
  - **Getters**: là computed property của store.
  - **Actions**: là nơi duy nhất được phép thay đổi state. **MUST NOT** thay đổi state trực tiếp từ component.
- **Actions (MUST)**: action có thể `async`. **MUST** `try/catch` cho các async action.

---

## 7. Composables (Composition API) (MUST)

- **Primary Logic Reuse (MUST)**: composable là cách chính để tái sử dụng logic (thay thế hoàn toàn mixins).
- **Naming (MUST)**: bắt đầu bằng `use...`.
- **Pureness (SHOULD)**: composable nên là pure function nếu có thể, không gây side-effect ngoài mong muốn.
- **Input/Output (MUST)**: nhận `ref`/`computed` làm input và return `ref`/`computed`/`function`.

---

## 8. Data Fetching (MUST)

- **Standardization (MUST)**: dùng `useFetch` hoặc `useAsyncData` cho mọi tác vụ fetch data phía client.
- **Centralized API Calls (SHOULD)**: tạo một composable `useApiFetch` để wrap `useFetch`, tự động thêm `baseURL`, `headers` (như `Authorization`), và xử lý lỗi chung.
- **Error Handling (MUST)**: luôn xử lý `error` state trả về từ `useFetch`.
- **Loading State (MUST)**: luôn xử lý `pending` state để hiển thị UI loading.
- **Key (MUST)**: cung cấp `key` cho `useFetch`/`useAsyncData` khi cần refresh/invalidate cache thủ công.

---

## 9. Server Engine (Nitro) & API Routes

- **API Handler Responsibility (MUST)**: API handler (`server/api/**/*.ts`) chỉ làm nhiệm vụ điều phối:
  1. Đọc/validate input (params, body).
  2. Authorize.
  3. Gọi logic xử lý (service/util phía server).
  4. Trả về response.
  - **MUST NOT** chứa business logic phức tạp.
- **Security (MUST)**: **MUST NOT** expose secret/API key ra client. Mọi call tới 3rd party API có key phải thực hiện qua server route.
- **Error Handling (MUST)**: dùng `createError` và `sendError` để trả về lỗi HTTP chuẩn.

---

## 10. Styling

- **Scoped Styles (MUST)**: mặc định dùng `<style scoped>`.
- **Global Styles (SHOULD)**: style global (VD: layout, reset CSS) đặt trong `assets/css/main.css` và import trong `nuxt.config.ts`.
- **Tailwind CSS (Khuyến nghị)**: nếu dùng, **MUST** định nghĩa design token (colors, spacing, typography) trong `tailwind.config.js`. **MUST NOT** dùng magic value trong class (`mt-[13px]`).

---

## 11. Error Handling

- **Global Error Page (MUST)**: tùy chỉnh file `error.vue` để hiển thị trang lỗi thân thiện.
- **Error Reporting (SHOULD)**: tích hợp service như Sentry/LogRocket qua plugin để report lỗi client-side và server-side.
- **`try/catch` (MUST)**: dùng trong Pinia actions, event handlers, và những nơi có thể phát sinh lỗi runtime.

---

## 12. Performance

- **Image Optimization (MUST)**: dùng `<NuxtImg>` thay vì `<img>` để tự động tối ưu ảnh (format, size).
- **Lazy Loading (SHOULD)**: lazy load component không cần thiết ngay khi render ban đầu bằng `defineAsyncComponent`.
- **`<ClientOnly>` (MUST)**: dùng cho component chỉ chạy ở client và không tương thích SSR.
- **Bundle Analysis (SHOULD)**: định kỳ kiểm tra bundle size với `nuxi analyze` để phát hiện dependency nặng.

---

## 13. Testing

- **Unit Testing (MUST)**: dùng Vitest để test:
  - Composables
  - Utils/Helpers
  - Pinia Stores
- **Component Testing (SHOULD)**: dùng Vue Test Utils để test component (đặc biệt là Dumb component).
- **E2E Testing (SHOULD)**: dùng Playwright/Cypress cho các user flow quan trọng (login, checkout).
- **Test Coverage (SHOULD)**: đặt mục tiêu coverage > 80% cho logic quan trọng.

---

## 14. Security (MUST)

- **Environment Variables (MUST)**:
  - Secret/private key **MUST** chỉ tồn tại ở server (runtime config) và không được expose ra client (`public` config).
  - Dùng file `.env` và không commit vào Git.
- **XSS (MUST)**: Nuxt/Vue tự động escape content trong template. **MUST NOT** dùng `v-html` với data từ user nếu không được sanitize cẩn thận.
- **CSRF (MUST)**: Nuxt 3.8+ có sẵn protection. Đảm bảo hiểu và không vô hiệu hoá nếu không có lý do.

---

## 15. PR Checklist (Tech Lead – 5 phút) (MUST)

- **Code Style**: code đã được format/lint?
- **TypeScript**: không có `any`? Type-hint đầy đủ?
- **Architecture**: component/composable đúng trách nhiệm? Không có logic trong view?
- **State**: state được quản lý đúng chỗ (local state vs Pinia)? Action có `try/catch`?
- **Data Fetching**: dùng `useFetch`? Có xử lý loading/error state?
- **Security**: không expose secret? Không dùng `v-html` bừa bãi?
- **Testing**: có unit test cho logic mới? Test có ý nghĩa?
