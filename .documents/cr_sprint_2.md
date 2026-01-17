🟩 SPRINT 2 – QUẢN LÝ LỚP HỌC & LỊCH HỌC
(Class, Schedule & Online/Offline Setup Sprint)
⏱ Thời lượng đề xuất: 2–3 tuần
🎯 Mục tiêu sprint:
Cho phép Teacher tạo và quản lý lớp học, thiết lập lịch học định kỳ, phân biệt Online / Offline / Hybrid, và xây dựng logic kiểm soát trùng lịch cho giáo viên & phòng học.
⚠️ Sprint này chưa xử lý học sinh, chưa điểm danh, chưa học phí.
Mục tiêu là xây xương sống CLASS + SCHEDULE, nếu sai → các sprint sau sẽ rất khó cứu.
________________________________________
I. PHẠM VI SPRINT 2
1️⃣ Làm trong Sprint 2
•	Class (Lớp học)
•	Class Type (ONLINE / OFFLINE / HYBRID)
•	Room (Phòng học – offline)
•	Schedule (Lịch học theo tuần)
•	Online meeting link (manual)
•	Logic check trùng:
o	Trùng phòng
o	Trùng giáo viên
2️⃣ Cố tình chưa làm
•	❌ Student trong lớp
•	❌ Attendance
•	❌ Học phí
•	❌ Video bài giảng
•	❌ Thông báo
________________________________________
II. KHÁI NIỆM NGHIỆP VỤ CỐT LÕI
________________________________________
1️⃣ CLASS (LỚP HỌC)
1.1 Khái niệm
Class là đơn vị dạy học cốt lõi, do Teacher tạo và sở hữu.
Mỗi Class:
•	Thuộc duy nhất 1 Teacher
•	Có loại hình học
•	Có lịch học định kỳ
📌 Class chưa có học sinh ở Sprint 2.
________________________________________
1.2 Thuộc tính Class
Field	Mô tả
id	ID lớp
teacher_id	Chủ lớp
name	Tên lớp
subject	Môn học
description	Mô tả
class_type	ONLINE / OFFLINE / HYBRID
status	ACTIVE / ARCHIVED
created_at	Ngày tạo
________________________________________
1.3 Quy tắc nghiệp vụ (BẮT BUỘC)
•	Teacher chỉ tạo lớp khi:
o	subscription.status = ACTIVE
•	Số lớp:
o	≤ price_plan.max_classes
•	Teacher:
o	❌ Không xem / sửa lớp của teacher khác
•	Class ARCHIVED:
o	❌ Không tạo lịch mới
o	✅ Chỉ xem lịch sử
________________________________________
2️⃣ CLASS TYPE (LOẠI HÌNH LỚP)
2.1 ONLINE
•	Có meeting_link
•	Không có room_id
•	Học sinh (future) tham gia bằng link
________________________________________
2.2 OFFLINE
•	Có room_id
•	Có địa chỉ vật lý
•	Phải check:
o	Trùng phòng
o	Trùng giáo viên
________________________________________
2.3 HYBRID
•	Có cả room_id và meeting_link
•	Dùng cho:
o	Học tại lớp + học online song song
________________________________________
III. QUẢN LÝ PHÒNG HỌC (OFFLINE)
________________________________________
3️⃣ ROOM (PHÒNG HỌC)
3.1 Khái niệm
Room là tài nguyên vật lý, chỉ dùng cho lớp OFFLINE / HYBRID.
________________________________________
3.2 Thuộc tính Room
Field	Mô tả
id	ID phòng
teacher_id	Chủ phòng
name	Ví dụ: Phòng 101
location	Địa chỉ
capacity	Sức chứa
status	ACTIVE / INACTIVE
________________________________________
3.3 Quy tắc nghiệp vụ
•	Room:
o	Thuộc 1 teacher
o	❌ Không dùng chung giữa teacher
•	Room đã từng dùng:
o	❌ Không xóa
o	✅ Chỉ cho INACTIVE
________________________________________
IV. LỊCH HỌC (SCHEDULE)
________________________________________
4️⃣ CLASS SCHEDULE
4.1 Khái niệm
Schedule định nghĩa lịch học lặp lại theo tuần cho Class.
📌 Sprint 2 chỉ hỗ trợ lịch tuần, chưa có lịch ngày cụ thể.
________________________________________
4.2 Thuộc tính Schedule
Field	Mô tả
id	ID
class_id	Lớp
day_of_week	2 → CN
start_time	Giờ bắt đầu
end_time	Giờ kết thúc
room_id	Nếu OFFLINE / HYBRID
meeting_link	Nếu ONLINE / HYBRID
created_at	
________________________________________
4.3 Quy tắc
•	1 Class:
o	Có 1 hoặc nhiều buổi / tuần
•	Thời gian:
o	start_time < end_time
•	Không cho schedule cho class ARCHIVED
________________________________________
V. ONLINE MEETING (LINK)
________________________________________
5️⃣ LINK ZOOM / GOOGLE MEET
5.1 Thiết lập
Teacher có thể:
•	Nhập link cố định cho lớp
•	Hoặc nhập theo từng schedule
📌 Sprint 2:
•	❌ Không tích hợp API Zoom
•	❌ Không tạo meeting tự động
________________________________________
5.2 Nút “BẮT ĐẦU DẠY”
•	Hiển thị cho Teacher
•	Click → redirect link
📌 Không tracking thời gian.
________________________________________
VI. LOGIC CHECK TRÙNG (RẤT QUAN TRỌNG)
________________________________________
6️⃣ TRÙNG PHÒNG (OFFLINE / HYBRID)
Khi tạo / sửa schedule:
•	Nếu có room_id
•	Check:
o	Cùng room
o	Cùng day_of_week
o	Overlap thời gian
→ ❌ Không cho lưu
________________________________________
7️⃣ TRÙNG GIÁO VIÊN
Teacher:
•	❌ Không được có 2 schedule:
o	Cùng ngày
o	Overlap time
•	Kể cả khác lớp, khác phòng
📌 Logic này áp dụng cho:
•	ONLINE
•	OFFLINE
•	HYBRID
________________________________________
VII. TRẠNG THÁI LỚP
________________________________________
8️⃣ CLASS STATUS
Status	Ý nghĩa
ACTIVE	Đang dạy
ARCHIVED	Kết thúc
📌 ARCHIVED:
•	Không tạo/sửa schedule
•	Không hiển thị trong danh sách tạo mới
________________________________________
VIII. LUỒNG NGHIỆP VỤ END-TO-END
Luồng 1 – Tạo lớp ONLINE
Teacher → Tạo lớp
→ Nhập thông tin
→ Chọn ONLINE
→ Nhập meeting_link
→ Tạo schedule
→ Hoàn tất
________________________________________
Luồng 2 – Tạo lớp OFFLINE
Teacher → Tạo lớp
→ Chọn OFFLINE
→ Chọn room
→ Tạo schedule
→ Check trùng
→ Hoàn tất
________________________________________
Luồng 3 – Tạo lớp HYBRID
Teacher → Tạo lớp
→ Chọn HYBRID
→ Chọn room
→ Nhập link
→ Tạo schedule
________________________________________
IX. PHÂN QUYỀN
Role	Quyền
Admin	Xem tất cả
Teacher	CRUD lớp của mình
Student	❌
________________________________________
X. YÊU CẦU KỸ THUẬT (TECHNICAL REQUIREMENTS)
Backend
•	Framework: Laravel
•	Database tables:
o	classes
o	rooms
o	class_schedules
Middleware / Service
•	check_subscription_active
•	check_quota_class
•	check_schedule_conflict
Nguyên tắc code
•	Tách logic:
o	ScheduleConflictService
•	Không hard-code thời gian
•	Tất cả query:
o	Filter theo teacher_id
________________________________________
XI. DEFINITION OF DONE – SPRINT 2
Sprint 2 hoàn thành khi:
•	✅ Teacher CRUD class
•	✅ Online / Offline / Hybrid
•	✅ CRUD phòng học
•	✅ Tạo lịch tuần
•	✅ Check trùng phòng
•	✅ Check trùng giáo viên
•	✅ Nút bắt đầu dạy hoạt động
________________________________________
XII. NHỮNG THỨ CỐ TÌNH CHƯA LÀM
•	❌ Student
•	❌ Attendance
•	❌ Học phí
•	❌ Video
•	❌ Notification
________________________________________
✅ SPRINT 2 – FINAL KẾT THÚC

