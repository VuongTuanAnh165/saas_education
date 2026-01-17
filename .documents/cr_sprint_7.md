🟩 SPRINT 7 – THÔNG BÁO, NHẮC LỊCH & BẢNG TIN (FINAL)
⏱ Thời lượng đề xuất: 2–3 tuần
🎯 Mục tiêu sprint:
Xây dựng hệ thống thông báo trong app, cho phép:
•	Tự động nhắc lịch học
•	Nhắc đóng học phí
•	Giáo viên đăng thông báo 1 chiều tới học sinh theo lớp
⚠️ Sprint này không phải hệ thống chat, không realtime, không tương tác hai chiều.
________________________________________
I. PHẠM VI SPRINT 7
✅ Làm trong sprint này
•	Notification core (in-app)
•	Reminder lịch học tự động
•	Nhắc đóng học phí
•	Bảng tin lớp (Announcement)
•	Trung tâm thông báo cho Teacher & Student
❌ Chưa làm trong sprint này
•	Chat realtime
•	Comment / like
•	Push mobile (FCM/APNs)
•	SMS thật (chỉ mock logic)
________________________________________
II. KHÁI NIỆM NGHIỆP VỤ CỐT LÕI
1️⃣ NOTIFICATION (THÔNG BÁO HỆ THỐNG)
1.1 Khái niệm
Notification là:
•	Một thông báo 1 chiều
•	Do hệ thống hoặc Teacher tạo
•	Gửi tới một user cụ thể
📌 Notification luôn là bản ghi cuối cùng mà user nhìn thấy
(Reminder / Announcement đều sinh ra Notification)
________________________________________
1.2 Thuộc tính Notification
Thuộc tính	Mô tả
id	
user_id	Người nhận
user_role	TEACHER / STUDENT
type	REMINDER / ANNOUNCEMENT / FINANCE
title	Tiêu đề
content	Nội dung
is_read	true / false
related_id	session_id / invoice_id / announcement_id
created_at	
________________________________________
III. NHẮC LỊCH HỌC (REMINDER)
2️⃣ REMINDER BUỔI HỌC
2.1 Nguyên tắc nhắc lịch
•	Nhắc trước buổi học
•	Áp dụng cho:
o	Teacher
o	Toàn bộ Student thuộc lớp
📌 Sprint 7:
•	Thời điểm nhắc cố định 30 phút trước giờ học
________________________________________
2.2 Thời điểm chạy reminder
•	Cron job chạy mỗi 5 phút
•	Điều kiện tạo reminder:
session.status = UPCOMING
AND session.start_time - now = 30 phút
________________________________________
2.3 Luồng xử lý
1.	Cron quét các session UPCOMING
2.	Với session sắp diễn ra:
o	Tạo Notification cho Teacher
o	Tạo Notification cho từng Student trong class
________________________________________
2.4 Nội dung mẫu
“Lớp Toán 9A sẽ bắt đầu lúc 18:00 hôm nay”
________________________________________
IV. THÔNG BÁO HỌC PHÍ
3️⃣ REMINDER ĐÓNG HỌC PHÍ
3.1 Điều kiện nhắc học phí
•	Invoice có trạng thái:
o	UNPAID
o	PARTIAL
________________________________________
3.2 Thời điểm nhắc
•	Cron chạy ngày 25 hằng tháng
•	Mỗi Invoice tạo 1 Notification / Student
📌 Sprint 7:
•	Chỉ nhắc trong app
•	Không gửi email / SMS
________________________________________
3.3 Nội dung mẫu
“Bạn còn học phí tháng 01/2026 chưa thanh toán”
________________________________________
V. BẢNG TIN LỚP (ANNOUNCEMENT)
4️⃣ ANNOUNCEMENT (THÔNG BÁO GIÁO VIÊN)
4.1 Khái niệm
Announcement là:
•	Một bài đăng 1 chiều
•	Do Teacher tạo
•	Thuộc về một Class
📌 Announcement không phải chat, không reply
________________________________________
4.2 Thuộc tính Announcement
Thuộc tính	Mô tả
id	
class_id	
teacher_id	
title	
content	
attachment_url	(optional)
created_at	
________________________________________
4.3 Quy tắc
•	Teacher tạo / sửa / xoá
•	Student chỉ được xem
•	Không comment, không like
________________________________________
5️⃣ Luồng đăng Announcement
1.	Teacher đăng bài
2.	Hệ thống:
o	Lưu Announcement
o	Sinh Notification cho toàn bộ Student trong Class
________________________________________
VI. TRẢI NGHIỆM NGƯỜI DÙNG
6️⃣ TRUNG TÂM THÔNG BÁO
Teacher & Student đều có:
•	Icon chuông
•	Danh sách Notification
•	Phân loại theo:
o	Nhắc lịch
o	Thông báo lớp
o	Học phí
•	Đánh dấu đã đọc
📌 Không cần filter nâng cao ở sprint này
________________________________________
VII. PHÂN QUYỀN & KIỂM SOÁT TRUY CẬP
Role	Quyền
Teacher	Tạo Announcement
Student	Đọc Notification
Admin	Read-only
📌 User chỉ thấy notification của chính mình
________________________________________
VIII. YÊU CẦU KỸ THUẬT (BẮT BUỘC – SPRINT 7)
1️⃣ Backend
•	Framework: Laravel
•	Modules:
o	Notifications
o	Announcements
•	Cron jobs:
o	Reminder lịch học
o	Reminder học phí
________________________________________
2️⃣ Database
•	notifications
•	announcements
________________________________________
3️⃣ Frontend
•	Web app:
o	Notification center
o	Announcement board theo class
•	UI đơn giản, không realtime
________________________________________
4️⃣ Chuẩn bị cho sprint sau
•	Thiết kế notification service:
o	Sau này mở rộng sang:
	Push mobile
	Email
	SMS
________________________________________
IX. LUỒNG NGHIỆP VỤ MẪU
Luồng 1: Nhắc lịch học
T-30 phút
→ Notification được tạo
→ Student mở app thấy thông báo
________________________________________
Luồng 2: Giáo viên thông báo nghỉ học
Teacher đăng announcement
→ Notification gửi tới lớp
→ Student đọc
________________________________________
X. DEFINITION OF DONE – SPRINT 7
•	✅ Notification core hoạt động
•	✅ Nhắc lịch học tự động
•	✅ Nhắc học phí
•	✅ Bảng tin lớp
•	✅ Trung tâm thông báo
________________________________________
XI. CHƯA LÀM TRONG SPRINT 7
•	❌ Chat realtime
•	❌ Push notification
•	❌ SMS thật
•	❌ Comment / Like
________________________________________
✅ SPRINT 7 – FINAL – HOÀN TẤT

