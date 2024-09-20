<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="resources/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="resources/uikit/css/uikit.modify.css">
        <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
        <link rel="stylesheet" href="resources/library/css/library.css">
        <link rel="stylesheet" href="resources/plugins/wow/css/libs/animate.css">
        <link rel="stylesheet" href="resources/style.css">
        <script src="resources/library/js/jquery.js"></script>
        <title>Home 2 | Economic Marketplace</title>
    </head>
    <body>
        
        
        <?php require 'include/header.php'  ?>


        <div id="homepage" class="homepage">
            <?php require 'include/slide.php'  ?>
            <div class="panel-category page-setup">
                <div class="uk-container uk-container-center">
                    <div class="panel-head">
                        <div class="uk-flex uk-flex-middle">
                            <h2 class="heading-1"><span>Danh mục sản phẩm</span></h2>
                            <div class="category-children">
                                <ul class="uk-list uk-clearfix uk-flex uk-flex-middle">
                                    <li class=""><a href="" title="">Bánh & Sữa</a></li>
                                    <li class=""><a href="" title="">Cà phê & Trà</a></li>
                                    <li class=""><a href="" title="">Thức ăn cho vật nuôi</a></li>
                                    <li class=""><a href="" title="">Rau củ</a></li>
                                    <li class=""><a href="" title="">Hoa Quả</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <?php $category = ['Cake & Milk','Oganic Kiwi','Peach','Read Apple','Snacks','Vegetables','Strawbery','Black plum','Custard apple','Coffe & Tea','Headphone','Kiwi','Iphone']  ?>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-container">
                            <div class="swiper-wrapper">
                                <?php for($i = 0; $i < count($category); $i++){  ?>
                                <div class="swiper-slide">
                                   <div class="category-item bg-<?php echo rand(1,7) ?>">
                                        <a href="" class="image img-scaledown img-zoomin"><img src="resources/img/cat-<?php echo $i + 1; ?>.png" alt=""></a>
                                        <div class="title"><a href="" title=""><?php echo $category[$i] ?></a></div>
                                        <div class="total-product"><?php echo rand(0, 100) ?> sản phẩm</div>
                                   </div>
                                </div>
                                <?php }  ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-banner">
                <div class="uk-container uk-container-center">
                    <div class="panel-body">
                        <div class="uk-grid uk-grid-medium">
                            <?php for($i = 1; $i<= 3; $i++){  ?>
                            <div class="uk-width-large-1-3">
                                <div class="banner-item">
                                    <span class="image"><img src="resources/img/banner-<?php echo $i; ?>.png" alt=""></span>
                                    <div class="banner-overlay">
                                        <div class="banner-title">Make your Breakfast healthy and Easy</div>
                                        <a class="btn-shop" title="">Mua ngay</a>
                                    </div>
                                </div>
                            </div>
                            <?php }  ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-popular">
                <div class="uk-container uk-container-center">
                    <div class="panel-head">
                        <div class="uk-flex uk-flex-middle uk-flex-space-between">
                            <h2 class="heading-1"><span>Sản phẩm nổi bật</span></h2>
                            <div class="category-children">
                                <ul class="uk-list uk-clearfix uk-flex uk-flex-middle">
                                    <li class=""><a href="" title="">Tất cả</a></li>
                                    <li class=""><a href="" title="">Bánh & Sữa</a></li>
                                    <li class=""><a href="" title="">Cà phê & Trà</a></li>
                                    <li class=""><a href="" title="">Thức ăn cho vật nuôi</a></li>
                                    <li class=""><a href="" title="">Rau củ</a></li>
                                    <li class=""><a href="" title="">Hoa Quả</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="uk-grid uk-grid-medium">
                            <?php for($i = 0; $i<=14; $i++){  ?>
                            <div class="uk-width-large-1-5 mb20">
                                <?php require 'include/product-item.php' ?>
                            </div>
                            <?php }  ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-bestseller">
                <div class="uk-container uk-container-center">
                    <div class="panel-head">
                        <div class="uk-flex uk-flex-middle uk-flex-space-between">
                            <h2 class="heading-1"><span>Sản phẩm bán chạy</span></h2>
                            <div class="category-children">
                                <ul class="uk-list uk-clearfix uk-flex uk-flex-middle">
                                    <li class=""><a href="" title="">Tất cả</a></li>
                                    <li class=""><a href="" title="">Bánh & Sữa</a></li>
                                    <li class=""><a href="" title="">Cà phê & Trà</a></li>
                                    <li class=""><a href="" title="">Thức ăn cho vật nuôi</a></li>
                                    <li class=""><a href="" title="">Rau củ</a></li>
                                    <li class=""><a href="" title="">Hoa Quả</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="uk-grid uk-grid-medium">
                            <div class="uk-width-large-1-4">
                                <div class="best-seller-banner">
                                    <a href="" class="image img-cover"><img src="resources/img/bestseller.png" alt=""></a>
                                    <div class="banner-title">Bring Natural<br> Into Your<br> Home</div>
                                </div>
                            </div>
                            <div class="uk-width-large-3-4">
                                <div class="product-wrapper">
                                    <div class="swiper-button-next"></div>
                                    <div class="swiper-button-prev"></div>
                                    <div class="swiper-container">
                                        <div class="swiper-wrapper">
                                            <?php for($i = 0; $i < count($category); $i++){  ?>
                                            <div class="swiper-slide">
                                                <?php require 'include/product-item.php'  ?>
                                            </div>
                                            <?php }  ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-deal page-setup">
                <div class="uk-container uk-container-center">
                    <div class="panel-head">
                        <div class="uk-flex uk-flex-middle uk-flex-space-between">
                            <h2 class="heading-1"><span>Giảm giá trong ngày</span></h2>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="uk-grid uk-grid-medium">
                            <?php for($i = 0; $i<=3; $i++){  ?>
                            <div class="uk-width-large-1-4">
                                <?php require 'include/product-item-2.php' ?>
                            </div>
                            <?php }  ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="uk-container uk-container-center">
                <div class="panel-group">
                    <div class="panel-body">
                        <div class="group-title">Stay home & get your daily <br> needs from our shop</div>
                        <div class="group-description">Start Your Daily Shopping with Nest Mart</div>
                        <span class="image img-scaledowm"><img src="resources/img/banner-9-min.png" alt=""></span>
                    </div>
                </div>
            </div>
            <div class="panel-commit">
                <div class="uk-container uk-container-center">
                    <div class="uk-grid uk-grid-medium">
                        <div class="uk-width-large-1-5">
                            <div class="commit-item">
                                <div class="uk-flex uk-flex-middle">
                                    <span class="image"><img src="resources/img/commit-1.png" alt=""></span>
                                    <div class="info">
                                        <div class="title">Giá ưu đãi</div>
                                        <div class="description">Khi mua từ 500.000đ</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="uk-width-large-1-5">
                            <div class="commit-item">
                                <div class="uk-flex uk-flex-middle">
                                    <span class="image"><img src="resources/img/commit-2.png" alt=""></span>
                                    <div class="info">
                                        <div class="title">Miễn phí vận chuyển</div>
                                        <div class="description">Trong bán kính 2km</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="uk-width-large-1-5">
                            <div class="commit-item">
                                <div class="uk-flex uk-flex-middle">
                                    <span class="image"><img src="resources/img/commit-3.png" alt=""></span>
                                    <div class="info">
                                        <div class="title">Ưu đãi</div>
                                        <div class="description">Khi đăng ký tài khoản</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="uk-width-large-1-5">
                            <div class="commit-item">
                                <div class="uk-flex uk-flex-middle">
                                    <span class="image"><img src="resources/img/commit-4.png" alt=""></span>
                                    <div class="info">
                                        <div class="title">Đa dạng </div>
                                        <div class="description">Sản phẩm đa dạng</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="uk-width-large-1-5">
                            <div class="commit-item">
                                <div class="uk-flex uk-flex-middle">
                                    <span class="image"><img src="resources/img/commit-5.png" alt=""></span>
                                    <div class="info">
                                        <div class="title">Đổi trả </div>
                                        <div class="description">Đổi trả trong ngày</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <?php require 'include/footer.php'  ?>
        <?php require 'include/popup.php'  ?>
        <div id="fb-root"></div>
        <script async defer crossorigin="anonymous" src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v17.0&appId=103609027035330&autoLogAppEvents=1" nonce="E1aWx0Pa"></script>
        <script src="resources/plugins/wow/dist/wow.min.js"></script>
        <script src="resources/uikit/js/uikit.min.js"></script>
        <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
        <script src="resources/uikit/js/components/sticky.min.js"></script>
        <script src="resources/function.js"></script>
    </body>
</html>

