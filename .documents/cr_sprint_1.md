🟩 SPRINT 1 – PRICING, SUBSCRIPTION & VÒNG ĐỜI GIÁO VIÊN
(Monetization & Teacher Lifecycle Sprint)
⏱ Thời lượng đề xuất: 2–3 tuần
🎯 Mục tiêu sprint:
Thiết lập mô hình bán gói SaaS, quản lý thời hạn sử dụng, và tự động khóa/mở Teacher & Student theo Subscription.
Sprint này chưa có nghiệp vụ lớp học, chỉ xây xương sống thương mại & vòng đời Teacher.
Nếu làm sai sprint này → hệ thống không thể scale, không thể thu tiền đúng.
________________________________________
I. MỤC TIÊU NGHIỆP VỤ (BUSINESS GOAL)
Sau Sprint 1, hệ thống bắt buộc phải đảm bảo:
1.	Admin có thể định nghĩa các gói dịch vụ (Price Plan)
2.	Mỗi Teacher tại 1 thời điểm:
o	Chỉ có 1 Subscription ACTIVE
3.	Subscription có:
o	Ngày bắt đầu
o	Ngày hết hạn
o	Trạng thái rõ ràng
4.	Hệ thống tự động khóa:
o	Teacher khi subscription hết hạn
o	Student thuộc teacher đó (khóa dây chuyền)
5.	Hệ thống có cơ chế kiểm tra giới hạn (quota) để làm nền cho:
o	Lớp học
o	Student
o	Video
o	Tài nguyên khác ở các sprint sau
________________________________________
II. KHÁI NIỆM NGHIỆP VỤ MỚI
________________________________________
1️⃣ PRICE PLAN (GÓI DỊCH VỤ)
1.1 Khái niệm
Price Plan là sản phẩm SaaS do Admin định nghĩa và bán cho Teacher.
•	Price Plan không gắn trực tiếp với Teacher
•	Teacher sử dụng Price Plan thông qua Subscription
📌 Nguyên tắc cứng
•	Không xóa Price Plan đã từng được dùng
•	Chỉ cho phép ẩn (is_active = false)
________________________________________
1.2 Các gói đề xuất (mang tính thực tế)
🟦 BASIC – Phổ thông
Phù hợp:
•	Giáo viên cá nhân
•	Dạy thêm quy mô nhỏ
Giới hạn:
•	max_classes: 3
•	max_students_per_class: 30
•	max_videos: 0
•	enable_video: ❌
•	enable_attendance: ❌
•	enable_schedule: ✅
•	enable_meeting_link: ✅
________________________________________
🟩 PRO – Phổ biến nhất
Phù hợp:
•	Trung tâm nhỏ
•	Dạy online + offline
Giới hạn:
•	max_classes: 10
•	max_students_per_class: 50
•	max_videos: 50
•	enable_video: ✅
•	enable_attendance: ✅
•	enable_schedule: ✅
•	enable_meeting_link: ✅
•	enable_material_upload: ✅
________________________________________
🟨 PREMIUM – Nâng cao
Phù hợp:
•	Trung tâm lớn
•	Scale nhiều lớp, nhiều GV
Giới hạn:
•	max_classes: NULL (không giới hạn)
•	max_students_per_class: NULL
•	max_videos: NULL
•	enable_all_features: ✅
•	enable_advanced_report: ✅
•	enable_export: ✅
•	enable_assistant_teacher: (future)
📌 Sprint 1 chỉ khai báo & lưu giới hạn — chưa triển khai full chức năng
________________________________________
2️⃣ SUBSCRIPTION (HỢP ĐỒNG SỬ DỤNG)
2.1 Khái niệm
Subscription là hợp đồng thời gian giữa:
•	Teacher (khách hàng)
•	Hệ thống SaaS (Admin)
________________________________________
2.2 Thuộc tính Subscription
Field	Ý nghĩa
id	Primary key
teacher_id	Owner
price_plan_id	Gói đang dùng
start_date	Ngày bắt đầu
end_date	Ngày hết hạn
status	ACTIVE / EXPIRED / CANCELLED
created_at	Thời điểm tạo
📌 Không có auto-renew ở Sprint 1
________________________________________
2.3 Quy tắc cứng
•	1 Teacher:
o	Có nhiều subscription (lịch sử)
o	Chỉ 1 subscription ACTIVE
•	Khi tạo subscription mới:
o	Subscription cũ → EXPIRED hoặc CANCELLED
________________________________________
III. PHẠM VI CHỨC NĂNG THEO ROLE
________________________________________
3️⃣ ADMIN – QUẢN LÝ PRICE PLAN
3.1 Tạo gói dịch vụ
Admin có màn hình:
•	name
•	price_per_year
•	description
•	Các quota:
o	max_classes
o	max_students_per_class
o	max_videos
•	Các flag:
o	enable_video
o	enable_attendance
o	enable_schedule
•	status:
o	ACTIVE / INACTIVE
📌 Không cho delete.
________________________________________
3.2 Chỉnh sửa gói
Admin có thể:
•	Thay đổi giá
•	Thay đổi quota
•	Thay đổi feature flag
📌 Không ảnh hưởng subscription đang chạy
________________________________________
4️⃣ ADMIN – QUẢN LÝ SUBSCRIPTION
4.1 Gán gói cho Teacher (manual)
Admin chọn:
•	Teacher
•	Price Plan
•	Thời hạn:
o	Mặc định: 1 năm
o	Hoặc custom start_date / end_date
Hệ thống:
•	Tạo subscription ACTIVE
•	Update teacher.subscription_id (nếu có)
________________________________________
4.2 Gia hạn
Admin:
•	Gia hạn thêm X tháng / năm
•	Cập nhật end_date
📌 Không có thanh toán online ở Sprint 1
________________________________________
5️⃣ TEACHER – XEM THÔNG TIN GÓI
Teacher có màn hình:
•	Tên gói
•	Ngày bắt đầu
•	Ngày hết hạn
•	Trạng thái
•	Các quota:
o	Đã dùng / tối đa
📌 Teacher không được đổi gói
________________________________________
IV. KIỂM SOÁT GIỚI HẠN (QUOTA CHECK)
6️⃣ NGUYÊN TẮC CHUNG
Mọi hành động tạo mới tài nguyên:
•	Lớp học
•	Student
•	Video
•	Tài nguyên future
👉 BẮT BUỘC:
1.	Check subscription tồn tại
2.	Check subscription.status = ACTIVE
3.	Check chưa vượt quota
________________________________________
6.2 Ví dụ
Teacher tạo lớp:
IF subscription != ACTIVE → reject
IF current_classes >= max_classes → reject
ELSE → allow
📌 Sprint 1 chỉ xây cơ chế, chưa triển khai full nghiệp vụ lớp.
________________________________________
V. TỰ ĐỘNG HẾT HẠN & KHÓA TÀI KHOẢN
7️⃣ CRON JOB HÀNG NGÀY
Hàng ngày hệ thống:
•	Scan subscription
•	Nếu today > end_date:
o	subscription.status = EXPIRED
o	teacher.status = SUSPENDED
________________________________________
7.2 Hiệu ứng dây chuyền
Khi teacher bị khóa:
•	Teacher login ❌
•	Student login ❌
📌 Dữ liệu không bị xóa
________________________________________
VI. LUỒNG NGHIỆP VỤ END-TO-END
Luồng 1 – Teacher sử dụng gói
Admin tạo gói PRO
→ Admin tạo Teacher
→ Admin gán gói PRO (1 năm)
→ Teacher login
→ Teacher sử dụng hệ thống
________________________________________
Luồng 2 – Hết hạn
Subscription hết hạn
→ Cron chạy
→ Teacher bị khóa
→ Student bị khóa
________________________________________
VII. DỮ LIỆU & TRẠNG THÁI
Subscription.status
•	ACTIVE
•	EXPIRED
•	CANCELLED
Teacher.status
•	ACTIVE
•	SUSPENDED
________________________________________
VIII. YÊU CẦU KỸ THUẬT (TECHNICAL REQUIREMENTS)
Backend
•	Framework: Laravel
•	Auth: dùng hệ thống auth từ Sprint 0
•	Database:
o	price_plans
o	subscriptions
•	Middleware:
o	check_subscription_active
o	check_quota
Cron
•	Laravel Scheduler
•	Chạy daily (00:00)
Nguyên tắc code
•	Không hard-code quota
•	Mọi quota lấy từ price_plan
•	Subscription logic tách service riêng
________________________________________
IX. DEFINITION OF DONE – SPRINT 1
Sprint 1 hoàn thành khi:
•	✅ Admin CRUD price plan
•	✅ Admin gán & gia hạn subscription
•	✅ Teacher xem được gói
•	✅ Cron hết hạn chạy đúng
•	✅ Teacher & Student bị khóa theo subscription
•	✅ Quota check sẵn sàng cho sprint sau
________________________________________
X. NHỮNG THỨ CỐ TÌNH CHƯA LÀM
•	❌ Thanh toán online
•	❌ Auto-renew
•	❌ Upgrade/Downgrade
•	❌ Refund
•	❌ Lớp học
•	❌ Video thực
________________________________________
✅ SPRINT 1 – FINAL KẾT THÚC

