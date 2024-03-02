<header class="inner-header section text-bg-black" data-aos="fade-in" data-aos-mirror="false">
  <div class="section__bg">
    <div class="section__bg--image overflow-hidden">
      <div class="section__bg-image-container h-100 op-50 rellax" data-rellax-speed="-2.5"
           data-rellax-tablet-speed="-2" data-rellax-xs-speed="-1.5">
        <img class="section__bg-image h-100"
             src="{{$page_header_image}}" alt=""
             width="1280" height="354" role="presentation" loading="lazy" data-aos="fade-in"
             data-aos-delay="1000"/>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-auto">
        <!-- Breadcrumb-->
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb p-0">
            @foreach($breadcrumbs as $item)
              <li class="breadcrumb-item text-uppercase" data-aos="fade-up" data-aos-delay="1250">
                <a class="link-white text-decoration-none" href="{{$item['link']}}">
                  {!! $item['title'] !!}
                </a>
              </li>
            @endforeach
          </ol>
        </nav>
        <!-- Breadcrumb End/.-->
      </div>
      <div class="col-12">
        <h1 class="mb-0 text-capitalize text-center" data-aos="fade-up" data-aos-delay="1000">
          <strong>{!! $page_header_title !!}</strong>
        </h1>
      </div>
    </div>
  </div>
</header>
