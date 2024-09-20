<div id="header" class="pc-header">
    <div class="header-upper">
        <div class="uk-container uk-container-center">
            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                <div class="sitelink">
                    <?php $sitelink = ['About Us','My Account','Wishlist','Order Tracking'];  ?>    
                    <ul class="uk-list uk-clearfix uk-flex uk-flex-middle">
                        <?php for($i = 0; $i < count($sitelink); $i++){  ?>
                        <li><a href="" title=""><?php echo $sitelink[$i] ?></a></li>
                        <?php }  ?>
                    </ul>
                </div>
                <div class="slogan">Siêu khuyến mãi, tiết kiệm tới 35% ngay hôm nay</div>
                <div class="header-widget">
                    <div class="uk-flex uk-flex-middle">
                        <div class="call-us">Cần hỗ trợ? Gọi cho chúng tôi: <a href="tel:098 236 5824" title="">098 236 5824</a></div>
                        <div class="language">
                            <a href="" class="active">Tiếng Việt</a>
                            <i class="fa fa-caret-down ml5" aria-hidden="true"></i>
                            <ul class="uk-list uk-clearfix language-dropdown">
                                <li><a href="" title="">Tiếng Anh</a></li>
                                <li><a href="" title="">Tiếng Trung</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!--  .header upper -->
    <div class="header-middle">
        <div class="uk-container uk-container-center">
            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                <div class="uk-flex uk-flex-middle">
                    <div class="logo">
                        <a href="" class="logo-image">
                            <img src="https://themepanthers.com/wp/nest/d1/wp-content/uploads/2022/02/logo.png" alt="">
                        </a>
                    </div>
                    <div class="header-form">
                        <form action="" class="uk-form form search-form">
                            <div class="form-row">
                                <input type="text" name="" class="input-text" placeholder="Tìm kiếm sản phẩm...">
                            </div>
                            <button type="submit" name="search" value="submit">Search</button>
                        </form>
                    </div>
                </div>
                <div class="middle-widget">
                    <div class="uk-flex uk-flex-middle">
                        <a href="" class="become-vendor">Trở thành đại lý <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                        <div class="widget-toolbox uk-flex uk-flex-middle">
                            <div class="toolbox-item">
                                <a href="" title="" class="uk-flex uk-flex-bottom">
                                    <div class="icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none">
                                            <g>
                                            <path d="M20.298 8.38686L21.7149 7.56033C18.6493 2.2939 12.0415 0.282014 6.56113 2.94644V0.930145H4.9212V5.84994H9.84099V4.21001H7.74598C12.3848 2.24224 17.7631 4.03197 20.298 8.38686Z" fill="#253D4E"></path>
                                            <path d="M5.33116 21.1635C1.52924 18.0758 0.528575 12.686 2.96884 8.43938L1.54702 7.61942C-1.2363 12.4662 -0.183154 18.6069 4.05611 22.2492H2.0513V23.8892H6.97109V18.9694H5.33116V21.1635V21.1635Z" fill="#253D4E"></path>
                                            <path d="M22.5209 11.2355L19.0426 14.7146L20.202 15.874L21.5959 14.4801C21.0492 19.5603 16.7683 23.4158 11.6588 23.43V25.0699C17.7465 25.0539 22.7967 20.3557 23.2514 14.2849L24.8405 15.874L26 14.7146L22.5209 11.2355Z" fill="#253D4E"></path>
                                            </g>
                                            <defs>
                                            <clipPath>
                                            <rect width="26" height="26" fill="white"></rect>
                                            </clipPath>
                                            </defs>
                                        </svg>
                                        <span class="number">0</span>
                                    </div>
                                    <div class="toolbox-text">So sánh</div>
                                </a>
                            </div>
                            <div class="toolbox-item">
                                <a href="" title="" class="uk-flex uk-flex-bottom">
                                    <div class="icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none">
                                            <g>
                                            <path d="M18.2753 1.28351C17.1493 1.30102 16.0478 1.61536 15.0821 2.19478C14.1164 2.7742 13.3207 3.59818 12.7753 4.58351C12.23 3.59818 11.4343 2.7742 10.4686 2.19478C9.50289 1.61536 8.4014 1.30102 7.27535 1.28351C5.48029 1.3615 3.78905 2.14676 2.57113 3.46774C1.35321 4.78872 0.707598 6.53803 0.775349 8.33351C0.775349 15.1085 11.7313 22.9335 12.1973 23.2655L12.7753 23.6745L13.3533 23.2655C13.8193 22.9355 24.7753 15.1085 24.7753 8.33351C24.8431 6.53803 24.1975 4.78872 22.9796 3.46774C21.7616 2.14676 20.0704 1.3615 18.2753 1.28351ZM12.7753 21.2125C9.52235 18.7825 2.77535 12.8125 2.77535 8.33351C2.70699 7.06822 3.14172 5.82724 3.98471 4.88121C4.82771 3.93518 6.01058 3.36086 7.27535 3.28351C8.54012 3.36086 9.72299 3.93518 10.566 4.88121C11.409 5.82724 11.8437 7.06822 11.7753 8.33351H13.7753C13.707 7.06822 14.1417 5.82724 14.9847 4.88121C15.8277 3.93518 17.0106 3.36086 18.2753 3.28351C19.5401 3.36086 20.723 3.93518 21.566 4.88121C22.409 5.82724 22.8437 7.06822 22.7753 8.33351C22.7753 12.8145 16.0283 18.7825 12.7753 21.2125Z" fill="#253D4E"></path>
                                            </g>
                                            <defs>
                                            <clipPath>
                                            <rect width="24" height="24" fill="white" transform="translate(0.775391 0.366516)"></rect>
                                            </clipPath>
                                            </defs>
                                        </svg>
                                        <span class="number">0</span>
                                    </div>
                                    <div class="toolbox-text">Yêu thích</div>
                                
                                </a>
                            </div>
                            <div class="toolbox-item">
                                <a href="" title="" class="uk-flex uk-flex-bottom">
                                    <div class="icon">
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
                                        <span class="number">0</span>
                                    </div>
                                    <div class="toolbox-text">Giỏ Hàng</div>
                                
                                </a>
                            </div>
                            <div class="toolbox-item">
                                <a href="" title="" class="uk-flex uk-flex-bottom">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none">
                                        <g>
                                        <path d="M21.4443 24.3665H19.4443V19.3235C19.4435 18.5395 19.1317 17.7879 18.5774 17.2335C18.023 16.6791 17.2713 16.3673 16.4873 16.3665H8.40134C7.61733 16.3673 6.86567 16.6791 6.3113 17.2335C5.75693 17.7879 5.44513 18.5395 5.44434 19.3235V24.3665H3.44434V19.3235C3.44592 18.0093 3.96869 16.7494 4.89796 15.8201C5.82723 14.8909 7.08714 14.3681 8.40134 14.3665H16.4873C17.8015 14.3681 19.0614 14.8909 19.9907 15.8201C20.92 16.7494 21.4427 18.0093 21.4443 19.3235V24.3665Z" fill="#253D4E"></path>
                                        <path d="M12.4443 12.3665C11.2577 12.3665 10.0976 12.0146 9.11092 11.3553C8.12422 10.696 7.35519 9.75898 6.90106 8.66262C6.44694 7.56626 6.32812 6.35986 6.55963 5.19598C6.79114 4.03209 7.36258 2.96299 8.2017 2.12388C9.04081 1.28476 10.1099 0.713318 11.2738 0.481807C12.4377 0.250296 13.6441 0.369116 14.7404 0.823242C15.8368 1.27737 16.7739 2.0464 17.4332 3.0331C18.0924 4.01979 18.4443 5.17983 18.4443 6.36652C18.4427 7.95733 17.8101 9.48253 16.6852 10.6074C15.5604 11.7323 14.0352 12.3649 12.4443 12.3665ZM12.4443 2.36652C11.6532 2.36652 10.8799 2.60111 10.2221 3.04064C9.56426 3.48017 9.05157 4.10488 8.74882 4.83579C8.44607 5.56669 8.36686 6.37096 8.5212 7.14688C8.67554 7.9228 9.0565 8.63554 9.61591 9.19495C10.1753 9.75436 10.8881 10.1353 11.664 10.2897C12.4399 10.444 13.2442 10.3648 13.9751 10.062C14.706 9.75929 15.3307 9.2466 15.7702 8.5888C16.2097 7.931 16.4443 7.15764 16.4443 6.36652C16.4443 5.30565 16.0229 4.28824 15.2728 3.53809C14.5226 2.78795 13.5052 2.36652 12.4443 2.36652Z" fill="#253D4E"></path>
                                        </g>
                                        <defs>
                                        <clipPath>
                                        <rect width="24" height="24" fill="white" transform="translate(0.444336 0.366516)"></rect>
                                        </clipPath>
                                        </defs>
                                        </svg>
                                    <div class="toolbox-text">Tài khoản</div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header-lower">
        <div class="uk-container uk-container-center">
            <div class="uk-flex uk-flex-middle">
                <div class="categories">
                    <span>Danh mục sản phẩm</span>
                    <?php $categories = ['Rượu & Đồ uống','Rau củ', 'Sữa','Hải sản','Hoa quả','Ưu đãi trong ngày','Thời trang','Bánh mỳ & nước ép','Nguyên liệu']  ?>
                    <div class="categories-dropdown">
                        <div class="uk-grid uk-grid-small">
                            <?php for($i = 0; $i<count($categories);$i++){  ?>
                            <div class="uk-width-large-1-2 mb10">
                                <div class="categories-item">
                                    <a href="" title="" class="uk-flex uk-flex-middle">
                                        <img src="resources/img/categories-<?php echo $i+1; ?>.png" alt="">
                                        <span class="title"><?php echo $categories[$i] ?></span>
                                        <span class="total"><?php echo rand(0, 20) ?></span>
                                    </a>
                                </div>
                            </div>
                            <?php }  ?>
                        </div>
                    </div>
                </div>
                <?php require 'navigation.php'; ?>
            </div>
        </div>
    </div>
</div>