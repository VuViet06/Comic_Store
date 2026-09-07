# 📋 BÁO CÁO PHÂN TÍCH DỰ ÁN COMIC STORE

> **Dự án**: Hệ thống bán truyện tranh trực tuyến (Comic Store)  
> **Công nghệ**: Laravel (PHP) + Blade + SQLite  
> **Ngày phân tích**: 07/09/2026  
> **Người phân tích**: QA Tester  

---

## 1. TỔNG QUAN DỰ ÁN

### 1.1 Mô tả
Comic Store là một ứng dụng web **thương mại điện tử (e-commerce)** chuyên bán truyện tranh. Hệ thống hỗ trợ bán hàng trực tuyến với đầy đủ luồng từ **duyệt sản phẩm → giỏ hàng → thanh toán → quản lý đơn hàng**, kèm theo hệ thống quản trị (Admin) toàn diện.

### 1.2 Kiến trúc tổng quan

```mermaid
graph TB
    subgraph "Frontend - Blade Views"
        A["Trang chủ / Danh sách truyện"]
        B["Chi tiết truyện"]
        C["Giỏ hàng"]
        D["Thanh toán"]
        E["Đơn hàng của tôi"]
        F["Trang Profile"]
        G["Admin Dashboard"]
    end

    subgraph "Backend - Controllers"
        H["ComicController"]
        I["CartController"]
        J["CheckoutController"]
        K["OrderController"]
        L["ProfileController"]
        M["Admin Controllers"]
    end

    subgraph "Service Layer"
        N["CartService"]
        O["CheckoutService"]
        P["OrderService"]
        Q["InventoryService"]
    end

    subgraph "Database Models"
        R["User"]
        S["Comic"]
        T["Order / OrderItem"]
        U["Voucher"]
        V["Shipment"]
        W["InventoryTransaction"]
        X["Category / Publisher"]
        Y["UserAddress"]
    end

    A --> H
    B --> H
    C --> I --> N
    D --> J --> O
    E --> K --> P
    F --> L
    G --> M
    O --> Q
    P --> Q
    H --> S
    I --> S
    J --> T
    K --> T
    M --> R & S & T & U & V & W & X
    L --> R & Y
```

### 1.3 Mô hình phân quyền

| Middleware | Mô tả | Áp dụng |
|---|---|---|
| `guest` | Chưa đăng nhập | Trang login, register |
| `auth` | Đã đăng nhập | Profile, đơn hàng |
| `admin` | Role = `admin` | Toàn bộ admin panel |
| `user` | Role = `user` | Dashboard user |
| *(không có)* | Public | Trang chủ, chi tiết truyện, giỏ hàng, tra cứu đơn |

---

## 2. NHẬN DIỆN ACTORS (TÁC NHÂN)

```mermaid
graph LR
    subgraph Actors
        G["🧑 Guest<br/>(Khách vãng lai)"]
        U["👤 User<br/>(Khách hàng đã đăng ký)"]
        A["👨‍💼 Admin<br/>(Quản trị viên)"]
    end

    G -->|"Đăng ký"| U
    U -->|"Được cấp quyền"| A
```

| Actor | Role DB | Mô tả |
|---|---|---|
| **Guest** | *(chưa có tài khoản)* | Người dùng chưa đăng nhập, có thể duyệt truyện, thêm giỏ hàng, đặt hàng, tra cứu đơn |
| **User** | `user` | Khách hàng đã đăng ký, có thể quản lý đơn hàng, profile, địa chỉ |
| **Admin** | `admin` | Quản trị viên, quản lý toàn bộ hệ thống |

---

## 3. PHÂN TÍCH LUỒNG NGHIỆP VỤ THEO TỪNG ACTOR

---

### 3.1 🧑 ACTOR: GUEST (Khách vãng lai)

#### 3.1.1 Luồng: Duyệt & Tìm kiếm truyện

```mermaid
flowchart TD
    A["Truy cập trang chủ /"] --> B["Xem danh sách truyện<br/>(phân trang 6/trang)"]
    B --> C{"Lọc/Tìm kiếm?"}
    C -->|"Có"| D["Lọc theo:<br/>- Thể loại (category)<br/>- NXB (publisher)<br/>- Loại bản (edition_type)<br/>- Khoảng giá (5 mức)<br/>- Còn hàng (in_stock)<br/>- Từ khóa (search)"]
    C -->|"Không"| E["Sắp xếp:<br/>- Mới nhất<br/>- Giá tăng/giảm<br/>- Tên A-Z<br/>- Bán chạy"]
    D --> E
    E --> F["Click xem chi tiết<br/>/comics/{slug}"]
    F --> G["Xem thông tin truyện:<br/>- Tên, mô tả, tác giả<br/>- NXB, thể loại, năm XB<br/>- Loại bản, series, tập<br/>- Giá, tồn kho<br/>- Truyện liên quan"]
```

**Các file liên quan:**
- Controller: [ComicController.php](file:///d:/Comic_Store/comic-store/app/Http/Controllers/ComicController.php)
- Model: [Comic.php](file:///d:/Comic_Store/comic-store/app/Models/Comic.php)
- Routes: `GET /` → `home`, `GET /comics/{slug}` → `comics.show`

**Chi tiết nghiệp vụ:**
- Chỉ hiển thị truyện **active** (`is_active = true`)
- Tìm kiếm AJAX: `GET /api/comics/search?q=` → trả JSON, giới hạn 10 kết quả
- API lấy categories/publishers: `GET /api/categories`, `GET /api/publishers`
- Truyện liên quan: cùng `category_id` hoặc cùng `series`, tối đa 4 truyện

---

#### 3.1.2 Luồng: Giỏ hàng (Session-based)

```mermaid
flowchart TD
    A["Thêm vào giỏ hàng<br/>POST /cart/add"] --> B{"Validate"}
    B -->|"comic_id hợp lệ<br/>quantity > 0"| C["CartService.add()"]
    B -->|"Không hợp lệ"| D["Trả lỗi 400"]
    C --> E["Lưu vào Session"]
    E --> F["Trả JSON: success,<br/>cart_count, item"]

    G["Xem giỏ hàng<br/>GET /cart"] --> H["CartService.getSummary()<br/>- items (chi tiết)<br/>- count<br/>- subtotal"]

    I["Cập nhật số lượng<br/>POST /cart/update"] --> J["CartService.updateQuantity()"]
    K["Xóa item<br/>POST /cart/remove"] --> L["CartService.remove()"]
    M["Xóa toàn bộ<br/>POST /cart/clear"] --> N["CartService.clear()"]

    O["Mini cart (dropdown)<br/>GET /cart/mini"] --> P["Trả JSON summary"]
    Q["Đếm items<br/>GET /cart/count"] --> R["Trả JSON count"]
```

**Các file liên quan:**
- Controller: [CartController.php](file:///d:/Comic_Store/comic-store/app/Http/Controllers/CartController.php)
- Service: [CartService.php](file:///d:/Comic_Store/comic-store/app/Services/CartService.php)

**Chi tiết nghiệp vụ:**
- Giỏ hàng lưu bằng **Session** → Guest cũng có thể dùng
- Tất cả thao tác (add/update/remove/clear) trả về **JSON** → xử lý bằng AJAX
- Validate: `comic_id` phải tồn tại, `quantity` phải hợp lệ

---

#### 3.1.3 Luồng: Thanh toán (Checkout)

```mermaid
flowchart TD
    A["Truy cập checkout<br/>GET /checkout"] --> B{"Giỏ hàng trống?"}
    B -->|"Có"| C["Redirect → /cart<br/>Thông báo lỗi"]
    B -->|"Không"| D["Validate giỏ hàng<br/>(còn hàng, active?)"]
    D -->|"Lỗi"| C
    D -->|"OK"| E["Hiển thị form checkout<br/>- Thông tin khách hàng<br/>- Địa chỉ giao hàng<br/>- Phương thức thanh toán<br/>- Ghi chú"]

    E --> F{"Áp dụng Voucher?"}
    F -->|"Có"| G["POST /checkout/apply-voucher<br/>Validate voucher:<br/>- Tồn tại & active<br/>- Còn trong thời hạn<br/>- Chưa hết lượt dùng<br/>- Đạt giá trị đơn tối thiểu"]
    G -->|"Hợp lệ"| H["Tính discount<br/>- Percent: (subtotal × value%)<br/>  có max_discount<br/>- Fixed: min(value, subtotal)"]
    G -->|"Không hợp lệ"| I["Trả lỗi 400"]

    E --> J["Submit đặt hàng<br/>POST /checkout"]
    J --> K["CheckoutService.createOrder()"]
    K --> L["DB::transaction"]
    L --> M["1. Validate cart lần nữa"]
    M --> N["2. Tính totals"]
    N --> O["3. Tạo Order<br/>code = ORD-XXXXXXXX<br/>status = pending<br/>payment = unpaid"]
    O --> P["4. Tạo OrderItems<br/>+ Trừ tồn kho"]
    P --> Q["5. Gắn voucher (nếu có)<br/>+ Tăng used_count"]
    Q --> R["6. Xóa giỏ hàng"]
    R --> S["Redirect → /checkout/success<br/>Hiển thị mã đơn hàng"]
```

**Các file liên quan:**
- Controller: [CheckoutController.php](file:///d:/Comic_Store/comic-store/app/Http/Controllers/CheckoutController.php)
- Service: [CheckoutService.php](file:///d:/Comic_Store/comic-store/app/Services/CheckoutService.php)

**Chi tiết nghiệp vụ quan trọng:**

| Bước | Mô tả | Ghi chú Test |
|---|---|---|
| Validate cart | Kiểm tra comic tồn tại, active, đủ stock | Test với comic bị xóa/hết hàng giữa chừng |
| Tạo mã đơn | `ORD-` + 8 ký tự random uppercase | Kiểm tra tính unique |
| Trừ kho | `InventoryService.reduceStock()` | Test race condition khi 2 user mua cùng lúc |
| Voucher | Percent hoặc Fixed, có max_discount | Test edge case: discount > subtotal |
| Transaction | Toàn bộ trong DB::transaction | Test rollback khi lỗi giữa chừng |

**Phương thức thanh toán hỗ trợ:**
- `cod` - Thanh toán khi nhận hàng
- `bank_transfer` - Chuyển khoản ngân hàng
- `momo` - Ví MoMo
- `vnpay` - VNPay

> [!IMPORTANT]
> Hiện tại chưa thấy tích hợp thực tế với MoMo/VNPay. Chỉ lưu `payment_method` vào DB, chưa có redirect thanh toán online.

---

#### 3.1.4 Luồng: Tra cứu đơn hàng (không cần đăng nhập)

```mermaid
flowchart TD
    A["Truy cập /orders/track"] --> B["Nhập mã đơn + SĐT"]
    B --> C["OrderService.trackOrder()"]
    C --> D{"Tìm thấy?"}
    D -->|"Có"| E["Hiển thị chi tiết đơn hàng"]
    D -->|"Không"| F["Thông báo lỗi:<br/>Không tìm thấy đơn hàng"]
```

**Chi tiết:** Tìm đơn theo `code` + `customer_phone`. Cho phép Guest kiểm tra trạng thái đơn mà không cần tài khoản.

---

#### 3.1.5 Luồng: Đăng ký / Đăng nhập

```mermaid
flowchart TD
    A["GET /register"] --> B["Nhập: name, email, password"]
    B --> C["POST /register"]
    C --> D["Tạo User (role = user)"]

    E["GET /login"] --> F["Nhập: email, password"]
    F --> G["POST /login"]
    G --> H{"Role?"}
    H -->|"admin"| I["Redirect → /admindashboard"]
    H -->|"user"| J["Redirect → /user/dashboard"]

    K["GET /forgot-password"] --> L["Nhập email"]
    L --> M["Gửi link reset password"]
    M --> N["GET /reset-password/{token}"]
    N --> O["Đặt mật khẩu mới"]
```

**Các file liên quan:** [auth.php](file:///d:/Comic_Store/comic-store/routes/auth.php)

**Chức năng xác thực:**
- Đăng ký, Đăng nhập, Đăng xuất
- Quên mật khẩu / Reset mật khẩu
- Xác thực email (verify email)
- Xác nhận mật khẩu (confirm password)

---

### 3.2 👤 ACTOR: USER (Khách hàng đã đăng ký)

> Kế thừa tất cả chức năng của Guest + các chức năng sau:

#### 3.2.1 Luồng: Quản lý đơn hàng

```mermaid
flowchart TD
    A["GET /my-orders<br/>Danh sách đơn hàng"] --> B["Lọc theo trạng thái:<br/>pending/shipping/<br/>completed/cancelled/returned"]
    B --> C["Hiển thị: thống kê + danh sách<br/>(15 đơn/trang)"]

    C --> D["GET /my-orders/{code}<br/>Chi tiết đơn hàng"]
    D --> E{"Kiểm tra quyền:<br/>order.user_id == Auth.id?"}
    E -->|"Không"| F["403 Forbidden"]
    E -->|"Có"| G["Hiển thị chi tiết"]

    G --> H{"Trạng thái?"}
    H -->|"pending"| I["Hủy đơn<br/>POST /my-orders/{code}/cancel"]
    H -->|"completed"| J["Yêu cầu hoàn trả<br/>POST /my-orders/{code}/return"]
    H -->|"shipping"| K["Chỉ xem, không có action"]

    I --> L["OrderService.cancelOrder()<br/>1. Check pending<br/>2. Hoàn trả tồn kho<br/>3. Status → cancelled"]

    J --> M["OrderService.requestReturn()<br/>1. Check completed<br/>2. Status → returned<br/>3. Ghi lý do vào customer_note"]
```

**Các file liên quan:**
- Controller: [OrderController.php](file:///d:/Comic_Store/comic-store/app/Http/Controllers/OrderController.php)
- Service: [OrderService.php](file:///d:/Comic_Store/comic-store/app/Services/OrderService.php)

**Thống kê user (getUserStats):**

| Metric | Mô tả |
|---|---|
| `total_orders` | Tổng số đơn |
| `pending` | Đang chờ xử lý |
| `shipping` | Đang vận chuyển |
| `completed` | Hoàn thành |
| `cancelled` | Đã hủy |
| `total_spent` | Tổng tiền đã chi (chỉ đơn completed) |

---

#### 3.2.2 Luồng: Quản lý Profile & Địa chỉ

```mermaid
flowchart TD
    A["GET /profile<br/>Chỉnh sửa profile"] --> B["Cập nhật thông tin<br/>PATCH /profile"]
    A --> C["Xóa tài khoản<br/>DELETE /profile"]

    D["Quản lý địa chỉ"] --> E["Thêm mới<br/>GET /profile/addresses/create<br/>POST /profile/addresses"]
    D --> F["Chỉnh sửa<br/>GET /profile/addresses/{id}/edit<br/>PUT /profile/addresses/{id}"]
    D --> G["Xóa<br/>DELETE /profile/addresses/{id}"]
    D --> H["Đặt mặc định<br/>POST /profile/addresses/{id}/default"]
    D --> I["API danh sách<br/>GET /profile/addresses/api/list"]
```

**Các file liên quan:**
- Controller: [ProfileController.php](file:///d:/Comic_Store/comic-store/app/Http/Controllers/ProfileController.php), [UserAddressController.php](file:///d:/Comic_Store/comic-store/app/Http/Controllers/UserAddressController.php)
- Model: [UserAddress.php](file:///d:/Comic_Store/comic-store/app/Models/UserAddress.php)

**Thông tin địa chỉ:**
- `label` - Nhãn (Nhà, Công ty...)
- `recipient_name` - Tên người nhận
- `phone` - Số điện thoại
- `address_line`, `ward`, `district`, `province`, `postal_code`
- `is_default` - Địa chỉ mặc định (chỉ 1 địa chỉ mặc định tại mỗi thời điểm)

---

### 3.3 👨‍💼 ACTOR: ADMIN (Quản trị viên)

#### 3.3.1 Tổng quan chức năng Admin

```mermaid
graph TD
    A["👨‍💼 ADMIN"] --> B["📊 Dashboard"]
    A --> C["📚 Quản lý Truyện"]
    A --> D["📦 Quản lý Đơn hàng"]
    A --> E["🏷️ Quản lý Thể loại"]
    A --> F["🏢 Quản lý NXB"]
    A --> G["🎫 Quản lý Voucher"]
    A --> H["📦 Quản lý Kho"]
    A --> I["👥 Quản lý Users"]
    A --> J["🚚 Quản lý Vận chuyển"]
```

**Đăng nhập Admin riêng:** `GET /admin` → `POST /admin` → sử dụng [AdminLoginController](file:///d:/Comic_Store/comic-store/app/Http/Controllers/Auth/AdminLoginController.php)

---

#### 3.3.2 Luồng: Dashboard

```mermaid
flowchart TD
    A["GET /admindashboard"] --> B["Thống kê đơn hàng<br/>- Tổng, pending, shipping<br/>- completed, cancelled<br/>- Hôm nay, tháng này"]
    A --> C["Thống kê doanh thu<br/>- Hôm nay<br/>- Tháng này<br/>- Tất cả"]
    A --> D["Thống kê sản phẩm<br/>- Tổng, active<br/>- Hết hàng, sắp hết"]
    A --> E["Thống kê user<br/>- Tổng, customers, admins<br/>- Mới hôm nay"]
    A --> F["10 đơn gần nhất"]
    A --> G["Top 10 bán chạy"]
    A --> H["Biểu đồ doanh thu<br/>7 ngày gần nhất"]
```

**File:** [DashboardController.php](file:///d:/Comic_Store/comic-store/app/Http/Controllers/Admin/DashboardController.php)

**Lưu ý cho Test:**
- Doanh thu chỉ tính đơn `completed`
- Sản phẩm `low_stock` = stock > 0 && stock ≤ 10
- Top bán chạy: JOIN `order_items` + `orders` WHERE `completed`

---

#### 3.3.3 Luồng: Quản lý Truyện (CRUD)

```mermaid
flowchart TD
    A["GET /admin/comics<br/>Danh sách truyện"] --> B["Thêm mới<br/>GET /admin/comics/create<br/>POST /admin/comics"]
    A --> C["Chỉnh sửa<br/>GET /admin/comics/{id}/edit<br/>PUT /admin/comics/{id}"]
    A --> D["Xóa<br/>DELETE /admin/comics/{id}"]
    A --> E["Xem chi tiết<br/>GET /admin/comics/{id}"]
```

**File:** [Admin/ComicController.php](file:///d:/Comic_Store/comic-store/app/Http/Controllers/Admin/ComicController.php)

**Thuộc tính Comic:**

| Field | Type | Mô tả |
|---|---|---|
| `title` | string | Tên truyện |
| `slug` | string | URL-friendly |
| `description` | text | Mô tả |
| `author` | string | Tác giả |
| `category_id` | FK | Thể loại |
| `publisher_id` | FK | Nhà xuất bản |
| `published_year` | int | Năm xuất bản |
| `edition_type` | string | Loại bản (thường, đặc biệt...) |
| `series` | string | Tên series |
| `volume` | int | Số tập |
| `price` | decimal | Giá bán |
| `cover` | string | Ảnh bìa |
| `stock` | int | Tồn kho |
| `is_active` | bool | Đang bán? |

---

#### 3.3.4 Luồng: Quản lý Đơn hàng

```mermaid
flowchart TD
    A["GET /admin/orders<br/>Danh sách đơn hàng"] --> B["Lọc: status, payment_status<br/>Tìm: code, tên, SĐT<br/>Lọc ngày: date_from, date_to<br/>Sắp xếp: mới/cũ/giá"]

    A --> C["GET /admin/orders/{code}<br/>Chi tiết đơn hàng"]

    C --> D["Cập nhật trạng thái<br/>POST /admin/orders/{code}/status"]
    C --> E["Hủy đơn<br/>POST /admin/orders/{code}/cancel"]
    C --> F["Xử lý hoàn trả<br/>POST /admin/orders/{code}/return"]

    D --> G{"Chuyển sang cancelled?"}
    G -->|"Có"| H["Hoàn trả tồn kho<br/>cho từng item"]
    G -->|"Không"| I["Chỉ cập nhật status"]

    E --> J{"Kiểm tra"}
    J -->|"Đã cancelled"| K["Lỗi: Đã bị hủy"]
    J -->|"Đã completed"| L["Lỗi: Không thể hủy"]
    J -->|"OK"| M["OrderService.cancelOrder()<br/>+ Hoàn trả kho"]

    F --> N{"Status = completed?"}
    N -->|"Không"| O["Lỗi: Chỉ hoàn trả đơn completed"]
    N -->|"Có"| P["1. Status → returned<br/>2. Ghi lý do<br/>3. Hoàn trả tồn kho"]
```

**File:** [Admin/OrderController.php](file:///d:/Comic_Store/comic-store/app/Http/Controllers/Admin/OrderController.php)

**State Machine - Trạng thái đơn hàng:**

```mermaid
stateDiagram-v2
    [*] --> pending: Đặt hàng
    pending --> shipping: Admin cập nhật
    pending --> cancelled: User/Admin hủy
    shipping --> completed: Admin cập nhật
    shipping --> cancelled: Admin hủy
    completed --> returned: User yêu cầu / Admin xử lý
    cancelled --> [*]
    returned --> [*]
    completed --> [*]
```

**State Machine - Trạng thái thanh toán:**

```mermaid
stateDiagram-v2
    [*] --> unpaid: Tạo đơn
    unpaid --> pending: Đang xử lý
    unpaid --> paid: Thanh toán thành công
    pending --> paid: Xác nhận
    pending --> failed: Thất bại
    paid --> refunded: Hoàn tiền
```

---

#### 3.3.5 Luồng: Quản lý Kho (Inventory)

```mermaid
flowchart TD
    A["GET /admin/inventory<br/>Danh sách tồn kho"] --> B["Lọc: out_of_stock,<br/>low_stock, in_stock<br/>Tìm kiếm theo tên"]
    A --> C["Sắp xếp: stock tăng/giảm, tên"]

    A --> D["Nhập hàng<br/>GET /admin/inventory/{id}/import"]
    D --> E["POST: quantity, notes"]
    E --> F["InventoryService.addStock()<br/>+ Tạo InventoryTransaction"]

    A --> G["Điều chỉnh kho<br/>GET /admin/inventory/{id}/adjust"]
    G --> H["POST: quantity_change, reason"]
    H --> I["InventoryService.adjustStock()<br/>+ Tạo InventoryTransaction"]

    A --> J["Lịch sử kho<br/>GET /admin/inventory/history<br/>GET /admin/inventory/{id}/history"]
    J --> K["Lọc: type, date_from, date_to<br/>Hiển thị: comic, user, order, note"]
```

**File:** [Admin/InventoryController.php](file:///d:/Comic_Store/comic-store/app/Http/Controllers/Admin/InventoryController.php), [InventoryService.php](file:///d:/Comic_Store/comic-store/app/Services/InventoryService.php)

**Loại giao dịch kho (InventoryTransaction):**

| Type | quantity_change | Khi nào |
|---|---|---|
| `import` | `+N` | Admin nhập hàng |
| `adjust` | `+N` hoặc `-N` | Admin điều chỉnh |
| `sale` | `-N` | Đặt hàng thành công |
| `restore` | `+N` | Hủy đơn / Hoàn trả |

---

#### 3.3.6 Luồng: Quản lý Voucher

```mermaid
flowchart TD
    A["GET /admin/vouchers<br/>Danh sách voucher"] --> B["Lọc trạng thái:<br/>all, active, hidden,<br/>out_of_limit, expired, upcoming"]
    A --> C["Tìm kiếm theo code"]
    A --> D["CRUD: Tạo / Sửa / Xóa"]
    A --> E["Toggle trạng thái<br/>PATCH /admin/vouchers/{id}/toggle-status"]

    D --> F{"Xóa voucher"}
    F --> G{"Đã có đơn dùng?"}
    G -->|"Có"| H["Lỗi: Không thể xóa"]
    G -->|"Không"| I["Xóa thành công"]
```

**File:** [Admin/VoucherController.php](file:///d:/Comic_Store/comic-store/app/Http/Controllers/Admin/VoucherController.php)

**Thuộc tính Voucher:**

| Field | Type | Mô tả |
|---|---|---|
| `code` | string | Mã voucher (unique) |
| `type` | enum | `percent` hoặc `fixed` |
| `value` | decimal | Giá trị giảm |
| `max_discount` | decimal? | Giảm tối đa (cho percent) |
| `min_order_amount` | decimal? | Giá trị đơn tối thiểu |
| `usage_limit` | int? | Giới hạn lượt dùng |
| `used_count` | int | Đã dùng bao nhiêu lần |
| `starts_at` | datetime? | Bắt đầu hiệu lực |
| `ends_at` | datetime? | Hết hiệu lực |
| `is_active` | bool | Kích hoạt? |

---

#### 3.3.7 Luồng: Quản lý Thể loại & NXB (CRUD đơn giản)

| Chức năng | Routes | Controller |
|---|---|---|
| Thể loại | `resource('categories')` | [CategoryController.php](file:///d:/Comic_Store/comic-store/app/Http/Controllers/Admin/CategoryController.php) |
| Nhà xuất bản | `resource('publishers')` | [PublisherController.php](file:///d:/Comic_Store/comic-store/app/Http/Controllers/Admin/PublisherController.php) |

---

#### 3.3.8 Luồng: Quản lý Users

```mermaid
flowchart TD
    A["GET /admin/users<br/>Danh sách users"] --> B["Thêm mới<br/>GET /admin/users/create<br/>POST /admin/users"]
    A --> C["Chỉnh sửa<br/>GET /admin/users/{id}/edit<br/>PUT /admin/users/{id}"]
    A --> D["Xóa<br/>DELETE /admin/users/{id}"]
```

**File:** [Admin/UserController.php](file:///d:/Comic_Store/comic-store/app/Http/Controllers/Admin/UserController.php)

---

#### 3.3.9 Luồng: Quản lý Vận chuyển

```mermaid
flowchart TD
    A["Quản lý đối tác<br/>GET /admin/shipping"] --> B["CRUD đối tác vận chuyển<br/>(ShippingPartner)"]
    B --> C{"Xóa đối tác"}
    C --> D{"Có shipment đang dùng?"}
    D -->|"Có"| E["Lỗi: Không thể xóa"]
    D -->|"Không"| F["Xóa thành công"]

    G["Danh sách đơn vận chuyển<br/>GET /admin/shipping/shipments"] --> H["Lọc: partner_id, status<br/>Tìm: tracking_code, order code"]
```

**File:** [Admin/ShippingController.php](file:///d:/Comic_Store/comic-store/app/Http/Controllers/Admin/ShippingController.php)

---

## 4. SƠ ĐỒ QUAN HỆ DỮ LIỆU (ERD)

```mermaid
erDiagram
    User ||--o{ Order : "đặt hàng"
    User ||--o{ UserAddress : "có nhiều địa chỉ"
    User ||--o{ InventoryTransaction : "thực hiện"

    Category ||--o{ Comic : "phân loại"
    Publisher ||--o{ Comic : "xuất bản"

    Comic ||--o{ OrderItem : "được mua"
    Comic ||--o{ InventoryTransaction : "biến động kho"

    Order ||--o{ OrderItem : "chứa"
    Order }o--o{ Voucher : "sử dụng (order_vouchers)"
    Order ||--o{ Shipment : "vận chuyển"
    Order ||--o{ InventoryTransaction : "liên quan"

    ShippingPartner ||--o{ Shipment : "thực hiện"

    FlashSale }o--o{ Comic : "khuyến mãi (flash_sale_comic)"

    User {
        int id PK
        string name
        string email
        string password
        string role
    }

    Comic {
        int id PK
        int category_id FK
        int publisher_id FK
        string title
        string slug
        decimal price
        int stock
        boolean is_active
    }

    Order {
        int id PK
        int user_id FK
        string code
        decimal subtotal_amount
        decimal discount_amount
        decimal total_amount
        string order_status
        string payment_status
        string payment_method
    }

    Voucher {
        int id PK
        string code
        string type
        decimal value
        int usage_limit
        int used_count
    }
```

---

## 5. MA TRẬN CHỨC NĂNG THEO ACTOR

| # | Chức năng | Guest | User | Admin |
|---|---|:---:|:---:|:---:|
| 1 | Xem danh sách truyện | ✅ | ✅ | ✅ |
| 2 | Tìm kiếm / Lọc truyện | ✅ | ✅ | ✅ |
| 3 | Xem chi tiết truyện | ✅ | ✅ | ✅ |
| 4 | Thêm giỏ hàng | ✅ | ✅ | ✅ |
| 5 | Quản lý giỏ hàng | ✅ | ✅ | ✅ |
| 6 | Đặt hàng (Checkout) | ✅ | ✅ | ❌ |
| 7 | Tra cứu đơn hàng (mã + SĐT) | ✅ | ✅ | ❌ |
| 8 | Đăng ký tài khoản (`/register`) | ✅ | ❌ | ❌ |
| 9 | Đăng nhập User (`/login`) | ✅ | ✅ | ❌ |
| 10 | Đăng nhập Admin (`/admin`) | ❌ | ❌ | ✅ |
| 11 | Đăng xuất | ❌ | ✅ | ✅ |
| 12 | Quên / Đặt lại mật khẩu | ✅ | ✅ | ❌ |
| 13 | Xem danh sách đơn hàng | ❌ | ✅ | ❌ |
| 14 | Hủy đơn hàng | ❌ | ✅ | ❌ |
| 15 | Yêu cầu hoàn trả | ❌ | ✅ | ❌ |
| 16 | Quản lý profile | ❌ | ✅ | ❌ |
| 17 | Quản lý địa chỉ | ❌ | ✅ | ❌ |
| 18 | Dashboard thống kê | ❌ | ❌ | ✅ |
| 19 | CRUD Truyện | ❌ | ❌ | ✅ |
| 20 | CRUD Thể loại | ❌ | ❌ | ✅ |
| 21 | CRUD NXB | ❌ | ❌ | ✅ |
| 22 | Quản lý đơn hàng (cập nhật status) | ❌ | ❌ | ✅ |
| 23 | Hủy đơn (Admin) | ❌ | ❌ | ✅ |
| 24 | Xử lý hoàn trả | ❌ | ❌ | ✅ |
| 25 | Quản lý kho (nhập/điều chỉnh) | ❌ | ❌ | ✅ |
| 26 | Xem lịch sử kho | ❌ | ❌ | ✅ |
| 27 | CRUD Voucher | ❌ | ❌ | ✅ |
| 28 | Quản lý Users | ❌ | ❌ | ✅ |
| 29 | Quản lý đối tác vận chuyển | ❌ | ❌ | ✅ |
| 30 | Xem đơn vận chuyển | ❌ | ❌ | ✅ |

> [!NOTE]
> **Về Đăng nhập / Đăng ký:**
> - Trang `/login` và `/register` có middleware `guest` → chỉ truy cập được khi **chưa đăng nhập**. Sau khi đăng nhập thành công, Guest trở thành **User** hoặc **Admin** tùy theo `role` trong DB.
> - **User** đăng nhập qua `/login` → redirect về `/user/dashboard`
> - **Admin** có trang đăng nhập riêng tại `/admin` → redirect về `/admindashboard`
> - Cả User và Admin đều có thể đăng xuất (`POST /logout` hoặc `POST /admin/logout`)

---

## 6. LUỒNG NGHIỆP VỤ CHÍNH (END-TO-END)

### 6.1 Luồng mua hàng hoàn chỉnh

```mermaid
sequenceDiagram
    actor G as Guest/User
    participant W as Website
    participant Cart as CartService
    participant CO as CheckoutService
    participant Inv as InventoryService
    participant DB as Database

    G->>W: Duyệt truyện
    G->>W: Click "Thêm vào giỏ"
    W->>Cart: add(comic_id, qty)
    Cart->>Cart: Lưu Session

    G->>W: Vào trang Checkout
    W->>Cart: validate() + getSummary()
    Cart-->>W: items, subtotal

    opt Áp dụng Voucher
        G->>W: Nhập mã voucher
        W->>CO: applyVoucher(code, subtotal)
        CO->>DB: Validate voucher
        CO-->>W: discount amount
    end

    G->>W: Submit đặt hàng
    W->>CO: createOrder(items, data, voucher)

    rect rgb(230, 240, 255)
        Note over CO,DB: DB::transaction
        CO->>CO: validateCart()
        CO->>CO: calculateTotals()
        CO->>DB: Create Order (status=pending)
        loop Mỗi item
            CO->>DB: Create OrderItem
            CO->>Inv: reduceStock(comic_id, qty)
            Inv->>DB: comic.stock -= qty
            Inv->>DB: Create InventoryTransaction(sale)
        end
        opt Có voucher
            CO->>DB: Attach voucher + increment used_count
        end
    end

    CO-->>W: Order created
    W->>Cart: clear()
    W-->>G: Redirect → Success page
```

### 6.2 Luồng xử lý đơn hàng (Admin)

```mermaid
sequenceDiagram
    actor A as Admin
    participant W as Admin Panel
    participant OS as OrderService
    participant Inv as InventoryService
    participant DB as Database

    A->>W: Xem danh sách đơn hàng
    A->>W: Click vào đơn pending

    alt Xác nhận giao hàng
        A->>W: Cập nhật status → shipping
        W->>DB: order.status = shipping
    end

    alt Xác nhận hoàn thành
        A->>W: Cập nhật status → completed
        W->>DB: order.status = completed
    end

    alt Hủy đơn
        A->>W: Click Hủy đơn
        W->>OS: cancelOrder()
        OS->>DB: order.status = cancelled
        loop Mỗi item
            OS->>Inv: restoreStock(comic_id, qty)
            Inv->>DB: comic.stock += qty
            Inv->>DB: Create InventoryTransaction(restore)
        end
    end

    alt Xử lý hoàn trả
        A->>W: Click Hoàn trả (chỉ đơn completed)
        W->>OS: requestReturn()
        OS->>DB: order.status = returned
        loop Mỗi item
            W->>Inv: restoreStock(comic_id, qty)
            Inv->>DB: comic.stock += qty
            Inv->>DB: Create InventoryTransaction(restore)
        end
    end
```

---

## 7. NHẬN XÉT & GỢI Ý KIỂM THỬ

### 7.1 Những điểm cần lưu ý khi Test

> [!WARNING]
> **Race Condition - Tồn kho**: Khi 2 user cùng mua 1 sản phẩm có stock = 1, có thể xảy ra overselling vì chưa có cơ chế lock.

> [!WARNING]
> **Thanh toán online**: Các phương thức MoMo, VNPay chỉ mới lưu vào DB, chưa tích hợp thực tế. Cần verify đơn COD vs online.

> [!IMPORTANT]
> **Guest Checkout**: Guest có thể đặt hàng mà không cần đăng nhập (`user_id` nullable). Cần test luồng Guest vs User checkout riêng.

> [!NOTE]
> **Voucher used_count**: Khi đơn hàng bị hủy, `used_count` của voucher KHÔNG được giảm lại. Đây có thể là bug hoặc business rule cần xác nhận.

### 7.2 Checklist Test theo Actor

#### Guest Testing
- [ ] Duyệt truyện không cần đăng nhập
- [ ] Tìm kiếm, lọc, sắp xếp hoạt động đúng
- [ ] Giỏ hàng hoạt động qua session
- [ ] Checkout không cần đăng nhập
- [ ] Tra cứu đơn bằng mã + SĐT
- [ ] Không thể truy cập `/my-orders`, `/profile`, `/admin`

#### User Testing
- [ ] Đăng ký → Đăng nhập → Redirect đúng
- [ ] Xem/Hủy/Hoàn trả đơn hàng
- [ ] Không xem được đơn của user khác (403)
- [ ] CRUD địa chỉ, set default
- [ ] Không truy cập được admin routes

#### Admin Testing
- [ ] Đăng nhập admin riêng (`/admin`)
- [ ] Dashboard hiển thị số liệu chính xác
- [ ] CRUD truyện/thể loại/NXB/voucher/user
- [ ] Cập nhật status đơn hàng đúng flow
- [ ] Hủy/Hoàn trả đơn → kho tồn được hoàn lại
- [ ] Nhập hàng/Điều chỉnh kho → InventoryTransaction ghi log
- [ ] Không xóa được voucher/đối tác đang có dữ liệu liên quan

### 7.3 Boundary & Edge Cases

| Test Case | Mô tả | Expected |
|---|---|---|
| Stock = 0 | Mua truyện hết hàng | Lỗi validate |
| Stock = 1, qty = 2 | Mua nhiều hơn tồn kho | Lỗi validate |
| Comic inactive | Mua truyện đã ngưng bán | Lỗi validate |
| Voucher expired | Dùng voucher hết hạn | Lỗi: đã hết hạn |
| Voucher max usage | Dùng voucher hết lượt | Lỗi: hết lượt |
| Discount > Subtotal | Voucher fixed lớn hơn đơn | Total = 0 (max 0) |
| Hủy đơn shipping | User hủy đơn đang ship | Lỗi: chỉ hủy khi pending |
| Hoàn trả đơn pending | Hoàn trả đơn chưa completed | Lỗi: chỉ hoàn đơn completed |
| Xóa user có đơn hàng | Admin xóa user đã mua | Kiểm tra cascade/restrict |
| Xóa comic có order | Admin xóa truyện đã bán | Kiểm tra FK constraint |
