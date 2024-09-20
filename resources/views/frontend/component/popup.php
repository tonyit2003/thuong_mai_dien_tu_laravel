<div id="popup" class="uk-modal popupProduct">
    <div class="uk-modal-dialog modal-width-1138">
        <a class="uk-modal-close uk-close"></a>
        <?php   
            $colorImage = [
                'https://wp.alithemes.com/html/ecom/demo/assets/imgs/page/product/img-gallery-2.jpg',
                'https://wp.alithemes.com/html/ecom/demo/assets/imgs/page/product/img-gallery-1.jpg',
                'https://wp.alithemes.com/html/ecom/demo/assets/imgs/page/product/img-gallery-3.jpg',
                'https://wp.alithemes.com/html/ecom/demo/assets/imgs/page/product/img-gallery-4.jpg',
                'https://wp.alithemes.com/html/ecom/demo/assets/imgs/page/product/img-gallery-5.jpg',
                'https://wp.alithemes.com/html/ecom/demo/assets/imgs/page/product/img-gallery-6.jpg',
                'https://wp.alithemes.com/html/ecom/demo/assets/imgs/page/product/img-gallery-7.jpg',
            ]
        ?>
        <div class="popup-container">
            <div class="panel-body">
                <div class="uk-grid uk-grid-medium">
                    <div class="uk-width-large-1-2">
                        <div class="popup-gallery">
                            <div class="swiper-container">
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>
                                <div class="swiper-wrapper big-pic">
                                    <?php foreach($colorImage as $key => $val){  ?>
                                    <div class="swiper-slide" data-swiper-autoplay="2000">
                                        <a href="<?php echo $val ?>" class="image img-cover"><img src="<?php echo $val?>" alt="<?php echo $val ?>"></a>
                                    </div>
                                    <?php }  ?>
                                </div>
                                <div class="swiper-pagination"></div>
                            </div>
                            <div class="swiper-container-thumbs">
                                <div class="swiper-wrapper pic-list">
                                    <?php foreach($colorImage as $key => $val){  ?>
                                    <div class="swiper-slide">
                                        <span  class="image img-cover"><img src="<?php echo $val?>" alt="<?php echo $val ?>"></span>
                                    </div>
                                    <?php }  ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-width-large-1-2">
                        <div class="popup-product">
                            <h2 class="title"><span>Category:Smartphones Tags:Blue,Smartphone
                                SAMSUNG Galaxy S22 Ultra, 8K Camera & Video, Brightest Display Screen, S Pen Pro</span>
                            </h2>
                            <div class="rating">
                                <div class="uk-flex uk-flex-middle">
                                    <div class="author">By Tuan Nguyen</div>
                                    <div class="star">
                                        <?php for($i = 0; $i<=4; $i++){ ?>
                                        <i class="fa fa-star"></i>
                                        <?php }  ?>
                                    </div>
                                    <div class="rate-number">(65 reviews)</div>
                                </div>
                            </div>
                            <div class="price">
                                <div class="uk-flex uk-flex-bottom">
                                    <div class="price-sale">9.000.000đ</div>
                                    <div class="price-old">10.900.000đ</div>
                                </div>
                            </div>
                            <div class="description">
                                <p>8k super steady video</p>
                                <p>Nightography plus portait mode</p>
                                <p>50mp photo resolution plus bright display</p>
                                <p>Adaptive color contrast</p>
                                <p>premium design & craftmanship</p>
                                <p>Long lasting battery plus fast charging</p>
                            </div>
                            <div class="attribute">
                                <div class="attribute-item attribute-color">
                                    <div class="label">Color: <span>Pink Gold</span></div>
                                    <div class="uk-grid uk-grid-small">
                                        <?php foreach($colorImage as $key => $val){  ?>
                                        <div class="uk-width-large-1-10">
                                            <div class="color-item <?php if($key == 1){
                                                    echo 'outstock';
                                                }else if($key == 4){
                                                        echo 'active';
                                                }  ?>">
                                                <span class="image"><img src="<?php echo $val; ?>" alt=""></span>
                                            </div>
                                        </div>
                                        <?php }  ?>
                                    </div>
                                </div>
                                <div class="attribute-item attribute-color">
                                    <div class="label">Styles: <span>S22</span></div>
                                    <div class="attribute-value">
                                        <a href="" title="">S22 Ultra</a>
                                        <a href="" title="" class="active">S22</a>
                                        <a href="" title="" class="outstock">S22 + Standing Cover</a>
                                    </div>
                                </div>
                                <div class="attribute-item attribute-color">
                                    <div class="label">Size: <span>125GB</span></div>
                                    <div class="attribute-value">
                                        <a href="" title="" class="outstock">1GB</a>
                                        <a href="" title="" class="active">512GB</a>
                                        <a href="" title="">64GB</a>
                                        <a href="" title="">128GB</a>
                                        <a href="" title="">32GB</a>
                                    </div>
                                </div>
                            </div><!-- .attribute -->
                            <div class="quantity">
                                <div class="text">Quantity</div>
                                <div class="uk-flex uk-flex-middle">
                                    <div class="quantitybox uk-flex uk-flex-middle">
                                        <div class="minus quantity-button"><img src="resources/img/minus.svg" alt=""></div>
                                        <input type="text" name="" value="1" class="quantity-text">
                                        <div class="plus quantity-button"><img src="resources/img/plus.svg" alt=""></div>
                                    </div>
                                    <div class="btn-group uk-flex uk-flex-middle">
                                        <div class="btn-item btn-1"><a href="" title="">Add To Cart</a></div>
                                        <div class="btn-item btn-2"><a href="" title="">Buy Now</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>