<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<style>
  /* Hide desktop carousel on mobile */
  @media (max-width: 767.98px) {
    #reviewCarousel {
      display: none;
    }
  }

  /* Hide mobile carousel on desktop */
  @media (min-width: 768px) {
    #mobileCarousel {
      display: none;
    }
  }
</style>

<div class="container my-5">
  <h2 class="text-center mb-4">Reviews</h2>

  <!-- 👇 Mobile Carousel View (1 image per slide) -->
  <div id="mobileCarousel" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
      <!-- Slide 1 -->
      <div class="carousel-item active">
        <div class="col-12">
          <a href="https://maps.app.goo.gl/DDboPLg14HRtqJNu8?g_st=iwb">
            <img src="../images/raja.jpeg" class="d-block w-100" alt="Raja">
          </a>
        </div>
      </div>

      <!-- Slide 2 -->
      <div class="carousel-item">
        <div class="col-12">
          <a href="https://maps.app.goo.gl/emVgYd9GDDiK3p1t7?g_st=iwb">
            <img src="../images/shiram.jpeg" class="d-block w-100" alt="Shiram">
          </a>
        </div>
      </div>

      <!-- Slide 3 -->
      <div class="carousel-item">
        <div class="col-12">
          <a href="https://maps.app.goo.gl/ziigXJz4DUB1VmgTA?g_st=iwb">
            <img src="../images/priya.jpeg" class="d-block w-100" alt="Priya">
          </a>
        </div>
      </div>

      <!-- Slide 4 -->
      <div class="carousel-item">
        <div class="col-12">
          <a href="https://maps.app.goo.gl/9bSZ8cfhmfqNMRrY8?g_st=iwb">
            <img src="../images/pass.jpeg" class="d-block w-100" alt="Pass">
          </a>
        </div>
      </div>

      <!-- Slide 5 -->
      <div class="carousel-item">
        <div class="col-12">
          <a href="https://maps.app.goo.gl/XJB4HJe15eAid571A?g_st=iwb">
            <img src="../images/sajith.jpeg" class="d-block w-100" alt="Sajith">
          </a>
        </div>
      </div>

      <!-- Slide 6 -->
      <div class="carousel-item">
        <div class="col-12">
          <a href="https://maps.app.goo.gl/38LLz52UwKfGrkoB8?g_st=iwb">
            <img src="../images/sudhir.jpeg" class="d-block w-100" alt="Sudhir">
          </a>
        </div>
      </div>

      <!-- Slide 7 -->
      <div class="carousel-item">
        <div class="col-12">
          <a href="https://maps.app.goo.gl/wgeYtPVYCRHMyBsf6?g_st=iwb">
            <img src="../images/udhaya.jpeg" class="d-block w-100" alt="Udhaya">
          </a>
        </div>
      </div>

      <!-- Slide 8 -->
      <div class="carousel-item">
        <div class="col-12">
          <a href="https://maps.app.goo.gl/YY72czJn2b1BmR7w5?g_st=iwb">
            <img src="../images/pon.jpeg" class="d-block w-100" alt="Pon">
          </a>
        </div>
      </div>

      <!-- Slide 9 -->
      <div class="carousel-item">
        <div class="col-12">
          <a href="https://maps.app.goo.gl/ziigXJz4DUB1VmgTA?g_st=iwb">
            <img src="../images/priya.jpeg" class="d-block w-100" alt="Priya Again">
          </a>
        </div>
      </div>
    </div>

    <!-- Mobile controls -->
    <a class="carousel-control-prev" href="#mobileCarousel" role="button" data-slide="prev">
      <span class="carousel-control-prev-icon bg-dark rounded-circle" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#mobileCarousel" role="button" data-slide="next">
      <span class="carousel-control-next-icon bg-dark rounded-circle" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>

  <!-- 👇 Desktop Carousel View (unchanged) -->
  <div id="reviewCarousel" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <div class="row">
          <div class="col-md-4 mb-3">
            <a href="https://maps.app.goo.gl/DDboPLg14HRtqJNu8?g_st=iwb">
              <img src="../images/raja.jpeg" class="d-block w-100" alt="Raja">
            </a>
          </div>
          <div class="col-md-4 mb-3">
            <a href="https://maps.app.goo.gl/emVgYd9GDDiK3p1t7?g_st=iwb">
              <img src="../images/shiram.jpeg" class="d-block w-100" alt="Shiram">
            </a>
          </div>
          <div class="col-md-4 mb-3">
            <a href="https://maps.app.goo.gl/ziigXJz4DUB1VmgTA?g_st=iwb">
              <img src="../images/priya.jpeg" class="d-block w-100" alt="Priya">
            </a>
          </div>
        </div>
      </div>
      <div class="carousel-item">
        <div class="row">
          <div class="col-md-4 mb-3">
            <a href="https://maps.app.goo.gl/9bSZ8cfhmfqNMRrY8?g_st=iwb">
              <img src="../images/pass.jpeg" class="d-block w-100" alt="Pass">
            </a>
          </div>
          <div class="col-md-4 mb-3">
            <a href="https://maps.app.goo.gl/XJB4HJe15eAid571A?g_st=iwb">
              <img src="../images/sajith.jpeg" class="d-block w-100" alt="Sajith">
            </a>
          </div>
          <div class="col-md-4 mb-3">
            <a href="https://maps.app.goo.gl/38LLz52UwKfGrkoB8?g_st=iwb">
              <img src="../images/sudhir.jpeg" class="d-block w-100" alt="Sudhir">
            </a>
          </div>
        </div>
      </div>
      <div class="carousel-item">
        <div class="row">
          <div class="col-md-4 mb-3">
            <a href="https://maps.app.goo.gl/wgeYtPVYCRHMyBsf6?g_st=iwb">
              <img src="../images/udhaya.jpeg" class="d-block w-100" alt="Udhaya">
            </a>
          </div>
          <div class="col-md-4 mb-3">
            <a href="https://maps.app.goo.gl/YY72czJn2b1BmR7w5?g_st=iwb">
              <img src="../images/pon.jpeg" class="d-block w-100" alt="Pon">
            </a>
          </div>
          <div class="col-md-4 mb-3">
            <a href="https://maps.app.goo.gl/ziigXJz4DUB1VmgTA?g_st=iwb">
              <img src="../images/priya.jpeg" class="d-block w-100" alt="Priya Again">
            </a>
          </div>
        </div>
      </div>
      

      <!-- You can add more desktop slides here similar to the first one -->
    </div>

    <a class="carousel-control-prev" href="#reviewCarousel" role="button" data-slide="prev">
      <span class="carousel-control-prev-icon bg-dark rounded-circle" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#reviewCarousel" role="button" data-slide="next">
      <span class="carousel-control-next-icon bg-dark rounded-circle" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
</div>
