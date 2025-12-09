@extends('layouts.client.master')

@section('title', 'Giới thiệu')

@section('content')
<div class="abouts-page">
    <!-- Hero -->
    <section class="hero-banner mb-5 position-relative text-center text-white rounded-4 overflow-hidden hero-bg-bakery">
        <div class="hero-overlay position-absolute top-0 start-0 w-100 h-100"></div>
        <div class="container-fluid hero-content position-relative p-5">
        <h1 class="fw-bold display-5 mb-3 text-uppercase">Về Chúng Tôi</h1>
        <p class="lead mb-4 col-md-8 col-12 mx-auto">
            Câu chuyện tạo nên thương hiệu Tiệm bánh Kim Loan, nơi kết tinh những tinh hoa về những chiếc bánh ngọt chất lượng và sự tận tâm mỗi ngày.
        </p>
        <a href="#abouts" class="btn btn-light btn-lg px-3">
            Khám phá ngay <i class="fas fa-arrow-up-right-from-square ms-1"></i>
        </a>
        </div>
    </section>

  <section class="py-5" id="abouts">
    <div class="row align-items-center">
      <div class="col-md-6 mb-4 mb-md-0">
        <img src="{{ asset('/images/about_story.jpg') }}" class="img-fluid rounded-4 shadow-sm" alt="Tiệm bánh">
      </div>
      <div class="col-md-6 text-justify">
        <h2 class="text-success fw-semibold">Chuyện về Tiệm Bánh Kim Loan</h2>
        <p>Chào mừng đến với tiệm Bánh Kim Loan!</p>
        <p>Trải qua hơn 30 năm, tiệm Bánh Kim Loan đã không ngừng mang đến những dịch vụ bánh kem tốt nhất, được đúc kết từ lòng đam mê và tài năng của đội ngũ thợ bánh chuyên nghiệp. Với sứ mệnh xây dựng niềm tin từ chất lượng, Kim Loan tự hào là điểm đến uy tín và lâu đời nhất tại Tân Phú và Sài Gòn hoa lệ.</p>
        <p>Tại Kim Loan, chúng tôi không chỉ là nơi chế biến bánh mà còn là ngôi nhà của những kỷ niệm quý báu. Từ những buổi tiệc nhỏ tới những sự kiện trọng đại như sinh nhật, cưới hỏi, chúng tôi luôn sẵn sàng để làm hài lòng mọi khách hàng. Bởi với chúng tôi, mỗi chiếc bánh là một tác phẩm nghệ thuật, đậm chất lãng mạn và tinh tế.</p>
        <p>Chúng tôi cam kết luôn cập nhật những mẫu bánh độc đáo và hiện đại nhất, để mỗi lần đến Kim Loan, quý khách sẽ được trải nghiệm những sản phẩm tốt nhất.</p>
      </div>
    </div>
  </section>

  <section class="py-5">
    <div class="text-center mb-4">
        <h2 class="fw-bold text-uppercase bg-success text-white rounded-pill px-4 py-2 w-max-content mx-auto">Các loại bánh đặc trưng</h2>
        <p class="text-muted">Những sản phẩm để lại ấn tượng sâu sắc cho khách hàng và biết đến Kim Loan</p>
    </div>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
          <img src="{{ asset('/images/product.jpg') }}" class="card-img-top img-fluid" alt="Bánh kem sữa tươi">
          <div class="card-body">
            <h5 class="card-title text-center fw-bold">Bánh Su Kem</h5>
            <p class="card-text text-muted text-center">Chiếc bánh chỉ với 2,000đ nhưng được sử dụng các loại nguyên liệu tốt và có vị ngọt nhẹ và hơi béo tạo nên sự đặc trưng.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
          <img src="{{ asset('/images/product.jpg') }}" class="card-img-top img-fluid" alt="Bánh kem sữa tươi">
          <div class="card-body">
            <h5 class="card-title text-center fw-bold">Bánh Su Kem</h5>
            <p class="card-text text-muted text-center">Chiếc bánh chỉ với 2,000đ nhưng được sử dụng các loại nguyên liệu tốt và có vị ngọt nhẹ và hơi béo tạo nên sự đặc trưng.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
          <img src="{{ asset('/images/product.jpg') }}" class="card-img-top img-fluid" alt="Bánh kem sữa tươi">
          <div class="card-body">
            <h5 class="card-title text-center fw-bold">Bánh Su Kem</h5>
            <p class="card-text text-muted text-center">Chiếc bánh chỉ với 2,000đ nhưng được sử dụng các loại nguyên liệu tốt và có vị ngọt nhẹ và hơi béo tạo nên sự đặc trưng.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="py-5">
    <div class="text-center mb-4">
      <h2 class="fw-bold text-uppercase bg-success text-white rounded-pill px-4 py-2 w-max-content mx-auto">
        Đặt bánh theo yêu cầu
      </h2>
      <p class="text-muted">
        Không chỉ bán các mẫu bánh có sẵn, Kim Loan còn mang đến dịch vụ đặt bánh theo yêu cầu – nơi mọi ý tưởng đều có thể trở thành hiện thực.
      </p>
    </div>

    <div class="row text-center g-4">
      <div class="col-md-3 col-6">
        <div class="p-4 border rounded-4 shadow-sm h-100">
          <div class="display-5 text-success fw-bold mb-3"><i class="fa-solid fa-phone-volume"></i></div>
          <h5 class="fw-semibold">Bước 1</h5>
          <p class="text-muted mb-0">Liên hệ với Kim Loan qua Hotline hoặc Fanpage để được tư vấn chi tiết về loại bánh bạn muốn đặt.</p>
        </div>
      </div>
      <div class="col-md-3 col-6">
        <div class="p-4 border rounded-4 shadow-sm h-100">
          <div class="display-5 text-success fw-bold mb-3"><i class="fa-regular fa-image"></i></div>
          <h5 class="fw-semibold">Bước 2</h5>
          <p class="text-muted mb-0">Gửi mẫu bánh bạn mong muốn hoặc chia sẻ ý tưởng để đội ngũ Kim Loan thiết kế theo sở thích riêng của bạn.</p>
        </div>
      </div>
      <div class="col-md-3 col-6">
        <div class="p-4 border rounded-4 shadow-sm h-100">
          <div class="display-5 text-success fw-bold mb-3"><i class="fa-solid fa-file-pen"></i></div>
          <h5 class="fw-semibold">Bước 3</h5>
          <p class="text-muted mb-0">Cung cấp thông tin chi tiết cho đơn hàng như kích thước, hương vị, ngày nhận bánh và lời nhắn đặc biệt (nếu có).</p>
        </div>
      </div>
      <div class="col-md-3 col-6">
        <div class="p-4 border rounded-4 shadow-sm h-100">
          <div class="display-5 text-success fw-bold mb-3"><i class="fa-solid fa-credit-card"></i></div>
          <h5 class="fw-semibold">Bước 4</h5>
          <p class="text-muted mb-0">Thanh toán, xác nhận đơn hàng và đợi đến ngày nhận chiếc bánh được làm riêng cho bạn – ngọt ngào, trọn vẹn và đáng nhớ.</p>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection
