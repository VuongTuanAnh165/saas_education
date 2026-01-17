📘 MASTER SPEC
HỆ THỐNG SAAS QUẢN LÝ DẠY THÊM
(Private Tutor / Center Management Platform)
________________________________________
PHẦN I. TỔNG QUAN HỆ THỐNG
🎯 Mục tiêu sản phẩm
Xây dựng nền tảng SaaS giúp:
•	Giáo viên / trung tâm quản lý toàn bộ hoạt động dạy thêm
•	Học sinh học tập chủ động
•	Chủ hệ thống bán dịch vụ & scale
🧱 Kiến trúc công nghệ (định hướng)
•	Backend: Laravel (API-first, RBAC, Policy)
•	Frontend Web: NuxtJS
•	Mobile App: Capacitor (iOS / Android)
•	Auth: JWT / Sanctum
•	DB: MySQL / PostgreSQL
•	Storage: S3-compatible (video, file)
________________________________________
PHẦN II. MASTER SPRINT OVERVIEW (0 → 10)
________________________________________
🟩 SPRINT 0 – NỀN TẢNG & KHỞI TẠO HỆ THỐNG
Foundation & System Setup
🎯 Mục tiêu:
Tạo nền móng kỹ thuật & nghiệp vụ cho toàn bộ hệ thống.
🔑 Nội dung chính:
•	Teacher account
•	Subscription (gói dịch vụ)
•	Trạng thái teacher: ACTIVE / EXPIRED / LOCKED
•	RBAC cơ bản
•	System settings
•	Audit log nền tảng
👉 Kết quả:
Có thể kiểm soát ai được dùng hệ thống
________________________________________
🟩 SPRINT 1 – LỚP HỌC & HỌC SINH
Class & Student Management
•	CRUD lớp học
•	CRUD học sinh
•	Gán học sinh vào lớp
•	Trạng thái học sinh (ACTIVE / INACTIVE)
•	Import danh sách học sinh
👉 Hệ thống bắt đầu có dữ liệu giáo dục thực
________________________________________
🟩 SPRINT 2 – LỊCH HỌC & BUỔI HỌC
Schedule & Session
•	Tạo lịch học định kỳ
•	Session ONLINE / OFFLINE
•	Trạng thái session
•	Link học online
•	Thời gian học thực tế
👉 Hệ thống biết khi nào dạy, dạy cái gì
________________________________________
🟩 SPRINT 3 – ĐIỂM DANH & THEO DÕI HỌC TẬP
Attendance & Learning Tracking
•	Điểm danh theo session
•	Trạng thái PRESENT / ABSENT
•	Lịch sử đi học
•	Thống kê chuyên cần
👉 Dữ liệu học tập bắt đầu có giá trị
________________________________________
🟩 SPRINT 4 – BÀI TẬP, TÀI LIỆU & GIAO BÀI
Homework & Materials
•	Upload tài liệu
•	Giao bài tập
•	Học sinh xem / nộp bài
•	Theo dõi trạng thái bài tập
👉 Tạo vòng lặp học tập ngoài lớp
________________________________________
🟩 SPRINT 5 – HỌC PHÍ & TÀI CHÍNH
Tuition, Invoice & Finance
•	Fee plan (buổi / tháng / khóa)
•	Invoice & công nợ
•	Thu tiền thủ công
•	Xác nhận chuyển khoản
•	Thu – chi nội bộ
•	Dashboard tài chính cơ bản
👉 Hệ thống bắt đầu liên quan tiền
________________________________________
🟩 SPRINT 6 – VIDEO BÀI GIẢNG (LMS MINI)
Video Learning
•	Upload video bài giảng
•	Gán theo lớp / buổi
•	Mở khóa theo thời gian
•	Theo dõi lượt xem
•	Giới hạn dung lượng theo gói
👉 Tăng giá trị dịch vụ cho giáo viên
________________________________________
🟩 SPRINT 7 – THÔNG BÁO & NHẮC LỊCH
Notification & Announcement
•	Notification system
•	Nhắc lịch học
•	Nhắc học phí
•	Bảng tin lớp
•	Trung tâm thông báo
👉 Giảm quên lớp, quên học, quên đóng tiền
________________________________________
🟦 SPRINT 8 – BÁO CÁO & DASHBOARD
Analytics & Reports
•	Dashboard giáo viên
•	Báo cáo lớp
•	Báo cáo học sinh
•	Báo cáo học phí
•	Export Excel / PDF
👉 Giáo viên ra quyết định bằng dữ liệu
________________________________________
🟦 SPRINT 9 – STUDENT PORTAL & MOBILE
Student Experience
•	Student login
•	Student dashboard
•	Xem lịch, vào lớp online
•	Xem video bài giảng
•	In-app + push notification
•	Kiểm soát theo trạng thái teacher
👉 Hệ thống có người dùng cuối thật
________________________________________
🟦 SPRINT 10 – ADMIN, BILLING & SCALE
SaaS Operation
•	Admin dashboard
•	Quản lý giáo viên
•	Subscription & gia hạn
•	Trial
•	Voucher
•	Referral
•	Audit log & support
👉 SaaS hoàn chỉnh – bán được – scale được
________________________________________
PHẦN III. ROADMAP MỞ RỘNG (SPRINT 11+)
Đây là phần không bắt buộc, nhưng cực kỳ quan trọng để:
•	Giữ chân khách hàng
•	Tăng doanh thu
•	Vượt đối thủ
________________________________________
🔶 SPRINT 11 – PAYMENT GATEWAY & AUTO BILLING
•	VNPay / Momo / ZaloPay
•	QR động
•	Auto renew
•	Payment webhook
•	Nhắc thanh toán tự động
🎯 Giảm 80% thao tác tay
________________________________________
🔶 SPRINT 12 – PARENT PORTAL
•	Phụ huynh login
•	Xem lịch học
•	Xem điểm danh
•	Xem học phí
•	Nhận thông báo
🎯 Phụ huynh = người trả tiền
________________________________________
🔶 SPRINT 13 – BI & AI HỖ TRỢ GIÁO VIÊN
•	BI dashboard nâng cao
•	Cảnh báo học sinh nghỉ nhiều
•	Gợi ý lịch học
•	Gợi ý tăng học phí hợp lý
🎯 Giáo viên thông minh hơn
________________________________________
🔶 SPRINT 14 – CENTER MODE (NHIỀU GIÁO VIÊN)
•	Trung tâm
•	Phân quyền giáo vụ
•	Nhiều lớp – nhiều giáo viên
•	Doanh thu theo giáo viên
🎯 Mở rộng sang trung tâm lớn
________________________________________
🔶 SPRINT 15 – LMS NÂNG CAO
•	Quiz
•	Chấm điểm
•	Lộ trình học
•	Gamification
________________________________________
🔶 SPRINT 16 – PLATFORM & ECOSYSTEM
•	API công khai
•	Plugin
•	Marketplace giáo viên
•	White-label app
________________________________________
PHẦN IV. TỔNG KẾT CHIẾN LƯỢC
Giai đoạn	Ý nghĩa
Sprint 0–5	Nền tảng
Sprint 6–8	Giá trị
Sprint 9–10	Thương mại hóa
Sprint 11+	Scale & thắng

