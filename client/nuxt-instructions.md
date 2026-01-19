# Nuxt 4 + Vue 3 + TypeScript Coding Rules
Bộ rule chuẩn hoá để code/review/CI **enforceable** (team nhiều dev, maintain dài hạn)

Tài liệu này áp dụng cho project hiện tại với:
- **Nuxt** `^4.2.2`
- **Vue** `^3.5.x`
- **TypeScript** `^5.9.x`
- **Pinia** `^3.x` + `@pinia/nuxt`
- **@nuxtjs/i18n** `^10.x`
- **Vuetify** `^3.x`
- **ESLint** (antfu config + eslint 9)

## 0) Mức độ bắt buộc
- **MUST**: bắt buộc (vi phạm => reject PR).
- **SHOULD**: khuyến nghị mạnh (vi phạm phải có lý do trong PR).
- **MUST NOT**: cấm.

## 1) Nguyên tắc kiến trúc
- **Single Responsibility (MUST)**: 1 module (component/composable/store) chỉ có 1 trách nhiệm.
- **DRY (MUST)**: không copy-paste logic. Tách `composables/`, `utils/`, `stores/`.
- **KISS (MUST)**: ưu tiên cách đơn giản, rõ ràng.
- **Composition over inheritance (MUST)**: Composition API + composables, không dùng mixins.
- **Client/Server boundary (MUST)**: code phụ thuộc browser API (window/document/localStorage) phải được guard (ví dụ `import.meta.client`, `process.client`) hoặc bọc trong lifecycle client-only.

## 2) Quality gates (enforce bằng CI)
- **ESLint/Formatting (MUST)**: code phải pass ESLint + format theo config repo.
- **TypeScript strictness (SHOULD)**: ưu tiên bật strict/strong typing. Không “tắt lỗi” bằng `any`.
- **No dead code (MUST)**: không để file/exports không dùng.
- **No debug artifacts (MUST NOT)**: không commit `console.log` (trừ khi log có chủ đích + được TL duyệt).

## 3) Naming conventions
| Loại | Quy ước | Ví dụ |
| --- | --- | --- |
| Component | `PascalCase` | `UserProfileCard.vue` |
| Page route file | `kebab-case` | `user-profile.vue` |
| Layout | `kebab-case` | `default.vue`, `auth.vue` |
| Composable | `use` + `PascalCase`/`camelCase` | `useAuth()`, `useApiFetch()` |
| Pinia store id | `camelCase` | `useAuthStore()` -> id `auth` |
| Util | `camelCase` | `formatDate.ts` |
| Server API route | `kebab-case` + method suffix | `users.get.ts`, `auth/login.post.ts` |
| Type / Interface | `PascalCase` | `UserProfile`, `ApiError` |
| Const | `SCREAMING_SNAKE_CASE` | `API_TIMEOUT_MS` |

**MUST**: tên file/biến phải phản ánh ý nghĩa domain (không dùng `data1`, `tmp`, `handle2`).

## 4) Project structure (Nuxt conventions first)
Ưu tiên dùng đúng convention của Nuxt (auto-import, file-based routing), chỉ mở rộng khi có lý do.

```
client/
  assets/            # css/fonts/images compile-time
  components/        # reusable UI (khuyến nghị theo feature)
  composables/       # reusable logic (useX)
  layouts/           # app layouts
  middleware/        # route middleware
  pages/             # routes
  plugins/           # nuxt plugins
  public/            # static files served as-is
  server/
    api/             # nitro api routes
    middleware/      # nitro middleware
    utils/           # server-only helpers
  stores/            # pinia stores (khuyến nghị tên `stores/` thay vì `store/`)
  types/             # shared types
  utils/             # pure helpers (no Vue dependency)
```

- **Feature-based grouping (SHOULD)**: group theo feature trong `components/`, `composables/`, `stores/` (ví dụ `components/course/`, `stores/course.ts`).
- **Cross-cutting (MUST)**: util thuần phải nằm `utils/` (không import Vue/Nuxt APIs trong util).

## 5) Vue SFC rules
- **`<script setup lang="ts">` (MUST)** cho tất cả SFC.
- **Props/Emits typing (MUST)**:
  - dùng typed `defineProps` / `defineEmits`.
  - props optional cần default rõ ràng (hoặc dùng `withDefaults`).
- **Template safety (MUST)**: không truy cập property có thể `undefined` mà không guard (optional chaining / v-if).
- **Component size (SHOULD)**: nếu component vượt ~200-300 LOC logic, tách nhỏ theo UI + composable.
- **Style scoping (SHOULD)**: ưu tiên `scoped`.
  - **MUST** dùng global styles cho design-system/layout/tokens; không nhồi tất cả vào scoped.

## 6) State management (Pinia)
- **Khi nào dùng store (SHOULD)**: state chia sẻ nhiều nơi, hoặc cần persistence/sync.
- **Không thần thánh hoá store (MUST)**: state chỉ dùng trong 1 page/component tree => dùng `ref`/`reactive` local.
- **Mutation rule (SHOULD)**:
  - Có thể mutate state trong action *hoặc* trực tiếp (Pinia cho phép).
  - Nhưng **MUST** thống nhất theo project: mọi mutation phức tạp/async phải nằm trong action để trace dễ.
- **Async actions (MUST)**: luôn handle error (throw hoặc map thành state `error`). Không nuốt lỗi im lặng.
- **SSR (MUST)**: store phải SSR-safe (không đọc localStorage/cookie trực tiếp khi chạy server).

## 7) Composables (Nuxt/Vue)
- **Composable là unit reuse chính (MUST)**.
- **Return shape (SHOULD)**: return object có tên rõ (`data`, `pending`, `error`, `refresh`, `execute`).
- **Side effects (MUST)**: side-effect phải explicit (document bằng tên hàm, ví dụ `startPolling`, `stopPolling`).
- **Không import vòng (MUST NOT)**: composable A import composable B tạo vòng phụ thuộc.

## 8) Data fetching & API layer (Nuxt 4)
- **MUST** dùng `useFetch`/`useAsyncData` (SSR-friendly) cho data page-level.
- **MUST** phân biệt:
  - **Server API (`server/api`)**: nơi giữ secret, gọi 3rd-party cần key.
  - **Client fetch**: gọi server API hoặc backend public.
- **Centralized fetch (SHOULD)**: tạo `useApiFetch()` wrap `ofetch`/`$fetch` để:
  - set `baseURL`
  - attach auth header
  - normalize error shape
- **Key & cache (SHOULD)**:
  - page data cần cache => `useAsyncData(key, ...)` với `key` ổn định.
  - tránh key phụ thuộc object non-stable.
- **Error handling (MUST)**:
  - UI phải handle `pending/error/empty`.
  - API errors phải map thành message hữu ích cho user.

## 9) Nitro server routes
- **Handler responsibility (MUST)**: handler chỉ parse/validate/auth rồi gọi service.
- **Validation (MUST)**: input phải được validate (body/query/params). Không tin client.
- **Errors (MUST)**: throw `createError({ statusCode, statusMessage, data })` để chuẩn hoá.
- **No secrets on client (MUST)**: secret chỉ dùng server runtimeConfig (không đặt trong `public`).

## 10) Runtime config & env
- **MUST** dùng `runtimeConfig` (`nuxt.config.ts`) cho env.
- **MUST NOT** đọc `process.env.X` rải rác trong code runtime.
- **MUST** phân tách `runtimeConfig.public` (được expose) và private.

## 11) SSR/CSR correctness
- **MUST** tránh hydration mismatch:
  - dữ liệu random/timezone-dependent phải generate nhất quán hoặc chạy client-only.
  - component phụ thuộc DOM => bọc `ClientOnly` hoặc guard `import.meta.client`.
- **Cookie/Auth (MUST)**: đọc auth token theo cách SSR-compatible (ví dụ `useCookie`).

## 12) Styling (Vuetify + CSS)
- **MUST** tuân theo design tokens/theme của Vuetify (không hardcode màu bừa bãi).
- **SHOULD** hạn chế magic numbers trong style.
- **MUST** không override CSS global gây side-effect không dự đoán.

## 13) i18n (nuxt/i18n)
- **MUST**: text user-facing phải đi qua i18n (trừ admin/dev-only).
- **MUST NOT** hardcode string trong component nếu là sản phẩm multi-language.
- **SHOULD**: key naming theo namespace (ví dụ `course.list.title`).

## 14) Performance
- **SHOULD** dynamic import cho feature nặng.
- **MUST** chỉ dùng `<ClientOnly>` khi thật sự cần (lạm dụng sẽ mất SSR benefit).
- **Image optimization (SHOULD)**:
  - Nếu project cài `@nuxt/image` thì dùng `<NuxtImg>`.
  - Nếu **chưa cài** (project hiện tại chưa thấy), dùng `<img>` nhưng phải set `width/height`, lazy-loading và tối ưu file.

## 15) Security
- **XSS (MUST)**: tránh `v-html`. Nếu bắt buộc, phải sanitize.
- **Auth/session (MUST)**: token/cookie handling phải server-aware.
- **CSRF (NOTE)**: Nuxt không “auto CSRF” cho mọi setup. Nếu dùng cookie-session và endpoint mutate, phải có chiến lược CSRF (SameSite cookie + token/header + origin check) theo backend.

## 16) Testing (khuyến nghị theo mức)
- **SHOULD**: Vitest cho utils/composables/stores.
- **SHOULD**: component test cho UI critical.
- **SHOULD**: e2e (Playwright) cho flow quan trọng.

## 17) PR checklist (enforceable)
- **Correctness**: SSR-safe? không dùng browser API trên server?
- **Types**: không `any` tuỳ tiện, không non-null assertion bừa.
- **Data**: handle `pending/error/empty`, không bỏ qua lỗi.
- **Security**: không expose secret, không `v-html` unsanitized.
- **Perf**: không import nặng vào entry nếu không cần.
- **i18n**: không hardcode string user-facing.
