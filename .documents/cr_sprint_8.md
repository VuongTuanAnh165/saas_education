🟦 SPRINT 8 – BÁO CÁO, THỐNG KÊ & DASHBOARD (FINAL)
⏱ Thời lượng đề xuất: 2–3 tuần
🎯 Mục tiêu sprint:
Cung cấp hệ thống Dashboard & Báo cáo tổng hợp giúp Teacher:
•	Nắm nhanh tình hình lớp – học sinh – học phí
•	Ra quyết định quản lý
•	Xuất dữ liệu phục vụ đối soát, lưu trữ
📌 Sprint này chỉ tập trung Teacher, Admin xem toàn hệ thống ở mức read-only.
________________________________________
I. PHẠM VI SPRINT 8
✅ Làm trong sprint này
•	Dashboard tổng quan cho Teacher
•	Báo cáo theo:
o	Lớp học
o	Học sinh
o	Học phí / doanh thu
•	Biểu đồ cơ bản
•	Export Excel / PDF
❌ Chưa làm trong sprint này
•	BI nâng cao
•	So sánh nhiều năm
•	AI / gợi ý thông minh
•	Dashboard cho Student
________________________________________
II. KHÁI NIỆM NGHIỆP VỤ
1️⃣ REPORT & DASHBOARD
1.1 Khái niệm
•	Dashboard: dữ liệu tổng hợp realtime (hoặc cache ngắn hạn)
•	Report: dữ liệu tổng hợp theo khoảng thời gian xác định
📌 Sprint 8:
•	Report KHÔNG lưu snapshot
•	Luôn được tính từ dữ liệu gốc:
o	Attendance
o	Session
o	Invoice
o	Payment
________________________________________
III. DASHBOARD TỔNG QUAN (TEACHER)
2️⃣ DASHBOARD HOME
2.1 Mục đích
Trả lời nhanh cho giáo viên:
“Hôm nay – tháng này – lớp tôi đang ra sao?”
________________________________________
2.2 Chỉ số hiển thị (Widgets)
Widget	Ý nghĩa	Nguồn dữ liệu
Tổng lớp đang dạy	Class ACTIVE	Class
Tổng học sinh	Unique Student	Student
Buổi học trong tháng	Session COMPLETED	Session
Doanh thu tháng	Tổng Payment	Payment
Học phí chưa thu	Tổng Invoice còn nợ	Invoice
________________________________________
2.3 Luồng xử lý
1.	Teacher login
2.	Gọi API dashboard
3.	Backend:
o	Filter theo teacher_id
o	Aggregate dữ liệu theo tháng hiện tại
4.	Trả về JSON
5.	Frontend render widgets
________________________________________
IV. BÁO CÁO LỚP HỌC
3️⃣ CLASS REPORT
3.1 Mục đích
Giúp Teacher:
•	So sánh hiệu quả các lớp
•	Biết lớp nào:
o	Đông học sinh
o	Đi học đều
o	Thu tiền tốt
________________________________________
3.2 Dữ liệu hiển thị
Trường	Mô tả
Class name	
Số học sinh	Enrollment ACTIVE
Tổng buổi học	Session COMPLETED
% chuyên cần	Attendance
Tổng học phí	Invoice.total
Còn nợ	Invoice.total – paid
________________________________________
3.3 Công thức % chuyên cần (CHUẨN)
% = (Tổng số lượt PRESENT)
    / (Tổng session COMPLETED × số học sinh)
📌 Chỉ tính attendance của session COMPLETED
________________________________________
3.4 Drill-down
Teacher click vào 1 class:
→ Xem:
•	Danh sách học sinh
•	Lịch sử buổi học
•	Tình trạng học phí
________________________________________
V. BÁO CÁO HỌC SINH
4️⃣ STUDENT REPORT
4.1 Mục đích
Phát hiện sớm học sinh:
•	Hay nghỉ
•	Chậm đóng tiền
•	Có nguy cơ bỏ học
________________________________________
4.2 Dữ liệu hiển thị
Trường	Mô tả
Student name	
Lớp đang học	
% chuyên cần	Attendance
Tổng học phí	Invoice
Còn nợ	Invoice
Trạng thái	OK / RISK
________________________________________
4.3 Logic đánh dấu RISK
Student bị đánh dấu RISK nếu:
•	% chuyên cần < 70%
HOẶC
•	Có công nợ > 0 trong 2 kỳ liên tiếp
📌 Chỉ là gợi ý, không khóa học sinh
________________________________________
VI. BÁO CÁO HỌC PHÍ / DOANH THU
5️⃣ FINANCE REPORT
5.1 Mục đích
Giúp Teacher nắm:
•	Tổng tiền phải thu
•	Đã thu bao nhiêu
•	Lớp nào còn nợ nhiều
________________________________________
5.2 Báo cáo theo thời gian
•	Tháng
•	Quý
•	Năm
________________________________________
5.3 Dữ liệu hiển thị
Trường	Mô tả
Thời gian	
Tổng phải thu	Invoice.total
Đã thu	Payment.sum
Còn nợ	Invoice – Payment
________________________________________
5.4 Biểu đồ
•	Line chart: Doanh thu theo thời gian
•	Bar chart: Nợ theo lớp
________________________________________
VII. EXPORT BÁO CÁO
6️⃣ EXPORT EXCEL / PDF
6.1 Phạm vi export
•	Class report
•	Student report
•	Finance report
________________________________________
6.2 Quy tắc export
•	Xuất theo filter đang chọn
•	Có:
o	Tên giáo viên
o	Ngày xuất
•	File:
o	Excel (.xlsx)
o	PDF (simple table)
________________________________________
6.3 Luồng xử lý
Teacher click Export
→ Backend generate file
→ Trả link download
________________________________________
VIII. PHÂN QUYỀN
Role	Dashboard	Report	Export
Teacher	✅	✅	✅
Student	❌	❌	❌
Admin	View all	View all	Optional
📌 Admin chỉ xem, không chỉnh
________________________________________
IX. YÊU CẦU KỸ THUẬT (BẮT BUỘC)
1️⃣ Backend
•	Laravel
•	Service layer cho:
o	DashboardService
o	ReportService
________________________________________
2️⃣ Data aggregation
•	Không query nặng realtime
•	Áp dụng:
o	Cache theo ngày
o	Cache theo tháng
________________________________________
3️⃣ Frontend
•	Dashboard responsive
•	Loading skeleton
•	Filter rõ ràng (thời gian, lớp)
________________________________________
X. UX / PERFORMANCE NOTE
•	Dashboard load < 2s
•	Report load < 3s
•	Export xử lý async (job)
________________________________________
XI. DEFINITION OF DONE – SPRINT 8
•	✅ Dashboard tổng quan
•	✅ Class report
•	✅ Student report
•	✅ Finance report
•	✅ Biểu đồ cơ bản
•	✅ Export Excel / PDF
________________________________________
XII. SAU SPRINT 8, HỆ THỐNG ĐÃ ĐẠT ĐƯỢC
👉 Giáo viên có thể:
•	Quản lý lớp & học sinh
•	Theo dõi học phí
•	Xem điểm danh
•	Nhận thông báo
•	Ra quyết định dựa trên dữ liệu
________________________________________
✅ SPRINT 8 – FINAL – HOÀN TẤT

