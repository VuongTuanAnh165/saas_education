🟩 SPRINT 6 – VIDEO BÀI GIẢNG & TỰ HỌC (LMS MINI – BẢN FINAL)
⏱ Thời lượng đề xuất: 2–3 tuần
🎯 Mục tiêu sprint:
Xây dựng nền tảng LMS mini cho hệ thống SaaS dạy thêm, cho phép Teacher đăng video bài giảng, gán video theo lớp / buổi học / thời gian, Student xem video đúng quyền – đúng thời điểm, và kiểm soát dung lượng theo gói dịch vụ.
⚠️ Sprint này chưa tập trung vào streaming nâng cao, DRM hay học tập tương tác – chỉ xây nền tảng ổn định, thực tế, dễ mở rộng.
________________________________________
I. PHẠM VI SPRINT 6
✅ Làm trong sprint này
•	Quản lý Video bài giảng (upload, publish, ẩn)
•	Gán video theo:
o	Lớp học
o	Buổi học (Teaching Session)
o	Thời gian mở khóa
•	Student xem video theo quyền
•	Theo dõi lượt xem video cơ bản
•	Giới hạn dung lượng video theo gói Subscription
❌ Chưa làm trong sprint này
•	Live stream
•	Streaming chuyên nghiệp (HLS, adaptive bitrate)
•	DRM / chống tải video
•	Quiz / bài tập trong video
•	Báo cáo học tập nâng cao
________________________________________
II. KHÁI NIỆM NGHIỆP VỤ MỚI
1️⃣ VIDEO LESSON (BÀI GIẢNG VIDEO)
1.1 Khái niệm
Video Lesson là:
•	Một nội dung học tập dạng video
•	Do Teacher tạo
•	Thuộc về một Class
•	Có thể gán cho buổi học cụ thể hoặc chỉ mở theo thời gian
📌 Video Lesson không thay thế buổi học, mà là:
•	Video xem lại
•	Video học trước / học bù
•	Video giao bài về nhà
________________________________________
1.2 Thuộc tính Video Lesson
Thuộc tính	Mô tả
id	ID video
class_id	Thuộc lớp nào
teacher_id	Người đăng
session_id	(nullable) – gán buổi học
title	Tiêu đề
description	Mô tả
video_url	Đường dẫn file
duration	Thời lượng (giây)
file_size	Dung lượng (MB)
available_from	Thời điểm bắt đầu mở
available_to	(nullable) – thời điểm đóng
status	DRAFT / PUBLISHED
created_at	Thời điểm tạo
________________________________________
1.3 Quy tắc truy cập video
Student chỉ được xem video khi đồng thời thỏa mãn:
•	Thuộc Class của video
•	Trạng thái Student = ACTIVE
•	Video status = PUBLISHED
•	Thời gian hiện tại nằm trong available_from → available_to
________________________________________
III. UPLOAD & QUẢN LÝ VIDEO
2️⃣ UPLOAD VIDEO
2.1 Luồng upload video
Teacher thực hiện:
1.	Chọn Class
2.	Upload video
3.	Hệ thống kiểm tra:
o	Subscription còn hiệu lực
o	Gói có quyền dùng Video
o	Tổng dung lượng video còn đủ
4.	Upload file
5.	Lưu metadata video
6.	Video mặc định ở trạng thái DRAFT
________________________________________
2.2 Giới hạn dung lượng theo gói
Gói	Video
FREE	❌ Không hỗ trợ
BASIC	❌ Không hỗ trợ
PRO	10GB
PREMIUM	100GB
📌 Giới hạn:
•	Theo tổng dung lượng, không giới hạn số video
•	Khi vượt → không cho upload thêm
________________________________________
3️⃣ QUẢN LÝ VIDEO (Teacher)
Teacher có thể:
•	Tạo video
•	Chỉnh sửa metadata
•	Publish / Unpublish
•	Xóa video (soft delete)
📌 Không được sửa video của Teacher khác
________________________________________
IV. GÁN VIDEO THEO BUỔI / LỊCH HỌC
4️⃣ GÁN VIDEO THEO SESSION
4.1 Các cách gán
Video có thể:
•	Gán trực tiếp vào Teaching Session
•	Hoặc chỉ gán theo Class + thời gian mở
📌 Ứng dụng thực tế:
•	Online → upload video buổi học
•	Offline → giao video về nhà
•	Học bù → mở video có thời hạn
________________________________________
4.2 Logic hiển thị video
IF now >= available_from
AND (available_to IS NULL OR now <= available_to)
→ Student thấy video
________________________________________
V. TRẢI NGHIỆM STUDENT
5️⃣ STUDENT VIDEO CENTER
Student thấy:
•	Danh sách video theo từng Class
•	Trạng thái video:
o	Chưa mở
o	Đang mở
o	Đã xem
📌 Sprint 6 chưa có playlist phức tạp
________________________________________
6️⃣ VIDEO VIEW TRACKING
6.1 Theo dõi lượt xem
Mỗi lần Student mở video:
•	Ghi record Video View
Thuộc tính	Mô tả
id	
student_id	
video_id	
first_view_at	Lần mở đầu
last_view_at	Lần xem gần nhất
last_position	(optional)
📌 Sprint 6:
•	Chỉ cần tracking mở video
•	Không yêu cầu tracking % hoàn thành
________________________________________
VI. PHÂN QUYỀN & BẢO MẬT
7️⃣ PHÂN QUYỀN VIDEO
Role	Quyền
Teacher	CRUD video
Student	View video
Admin	Read-only
📌 Student không xem được video nếu không thuộc lớp
________________________________________
VII. YÊU CẦU KỸ THUẬT (BẮT BUỘC – SPRINT 6)
1️⃣ Backend
•	Framework: Laravel
•	Storage:
o	Local (dev)
o	S3-compatible (production)
•	API:
o	Upload video
o	List video theo class
o	Check quyền truy cập video
•	DB:
o	video_lessons
o	video_views
________________________________________
2️⃣ Frontend
•	Web App:
o	Teacher dashboard upload video
o	Student video center
•	Video player:
o	HTML5 video
o	Không DRM
o	Không streaming adaptive
________________________________________
3️⃣ Bảo mật cơ bản
•	Signed URL / private bucket
•	Chỉ trả link khi:
o	Authenticated
o	Có quyền xem
________________________________________
VIII. LUỒNG NGHIỆP VỤ MẪU
Luồng 1: Video cho lớp offline
Teacher upload video
→ Set available_from = ngày học
→ Student xem tại nhà
________________________________________
Luồng 2: Video xem lại online
Buổi học kết thúc
→ Teacher upload video
→ Publish
→ Student xem lại
________________________________________
IX. DEFINITION OF DONE – SPRINT 6
•	✅ Upload video
•	✅ Gán video theo lớp / buổi
•	✅ Mở khóa theo thời gian
•	✅ Giới hạn dung lượng theo gói
•	✅ Student xem video đúng quyền
•	✅ Tracking lượt xem cơ bản
________________________________________
X. CHƯA LÀM TRONG SPRINT 6
•	❌ Live stream
•	❌ DRM / chống download
•	❌ Quiz trong video
•	❌ Báo cáo học tập chi tiết
________________________________________
✅ Sprint 6 – FINAL – HOÀN TẤT

