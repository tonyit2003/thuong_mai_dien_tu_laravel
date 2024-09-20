<?php 
    $product = [
        0 => [
            'title' => 'Seeds of Change Organic Red Rice'
        ],
        1 => [
            'title' => 'All Natural Style Chicken Meatballs'
        ],
        2 => [
            'title' => 'Angie’s Sweet & Salty Kettle Corn'
        ],
        3 => [
            'title' => 'Blue Almonds Lightly Salted Vegetables'
        ]
    ]
?>
<div class="product-item product">
    <div class="badge badge-bg<?php echo rand(1,3) ?>">-<?php echo rand(10, 35) ?>%</div>
    <a href="" class="image img-cover"><img src="resources/img/product-<?php echo rand(1,10) ?>.jpg" alt=""></a>
    <div class="info">
        <div class="category-title"><a href="" title="">Fresh Fruit</a></div>
        <h3 class="title"><a href="" title=""><?php echo $product[rand(0, 3)]['title'] ?></a></h3>
        <div class="rating">
            <div class="uk-flex uk-flex-middle">
                <div class="star">
                    <?php for($j = 0; $j <= 4; $j++){  ?>
                    <i class="fa fa-star"></i>
                    <?php }  ?>
                </div>
                <span class="rate-number">(<?php echo rand(0, 1000) ?>)</span>
            </div>
        </div>
        <div class="product-group">
            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                <div class="price uk-flex uk-flex-bottom">
                    <div class="price-sale"><?php echo number_format(rand(600000, 5000000)) ?>đ</div>
                    <div class="price-old">400.000đ</div>
                </div>
                <div class="addcart">
                    <a href="" title="" class="btn-addCart">
                        <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g>
                            <path d="M24.4941 3.36652H4.73614L4.69414 3.01552C4.60819 2.28593 4.25753 1.61325 3.70863 1.12499C3.15974 0.636739 2.45077 0.366858 1.71614 0.366516L0.494141 0.366516V2.36652H1.71614C1.96107 2.36655 2.19748 2.45647 2.38051 2.61923C2.56355 2.78199 2.68048 3.00626 2.70914 3.24952L4.29414 16.7175C4.38009 17.4471 4.73076 18.1198 5.27965 18.608C5.82855 19.0963 6.53751 19.3662 7.27214 19.3665H20.4941V17.3665H7.27214C7.02705 17.3665 6.79052 17.2764 6.60747 17.1134C6.42441 16.9505 6.30757 16.7259 6.27914 16.4825L6.14814 15.3665H22.3301L24.4941 3.36652ZM20.6581 13.3665H5.91314L4.97214 5.36652H22.1011L20.6581 13.3665Z" fill="#253D4E"></path>
                            <path d="M7.49414 24.3665C8.59871 24.3665 9.49414 23.4711 9.49414 22.3665C9.49414 21.2619 8.59871 20.3665 7.49414 20.3665C6.38957 20.3665 5.49414 21.2619 5.49414 22.3665C5.49414 23.4711 6.38957 24.3665 7.49414 24.3665Z" fill="#253D4E"></path>
                            <path d="M17.4941 24.3665C18.5987 24.3665 19.4941 23.4711 19.4941 22.3665C19.4941 21.2619 18.5987 20.3665 17.4941 20.3665C16.3896 20.3665 15.4941 21.2619 15.4941 22.3665C15.4941 23.4711 16.3896 24.3665 17.4941 24.3665Z" fill="#253D4E"></path>
                            </g>
                            <defs>
                            <clipPath>
                            <rect width="24" height="24" fill="white" transform="translate(0.494141 0.366516)"></rect>
                            </clipPath>
                            </defs>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

    </div>
    <div class="tools">
        <a href="" title=""><img src="resources/img/trend.svg" alt=""></a>
        <a href="" title=""><img src="resources/img/wishlist.svg" alt=""></a>
        <a href="" title=""><img src="resources/img/compare.svg" alt=""></a>
        <a href="#popup" data-uk-modal title=""><img src="resources/img/view.svg" alt=""></a>
    </div>
</div>