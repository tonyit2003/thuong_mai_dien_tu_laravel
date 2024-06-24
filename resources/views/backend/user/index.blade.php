<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>{{ config('apps.user.title') }}</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('dashboard.index') }}">Dashboard</a>
            </li>
            <li class="active">
                <strong>{{ config('apps.user.title') }}</strong>
            </li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 mt20">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>{{ config('apps.user.tableHeading') }} </h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-wrench"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#">Config option 1</a>
                        </li>
                        <li><a href="#">Config option 2</a>
                        </li>
                    </ul>
                    <a class="close-link">
                        <i class="fa fa-times"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">

                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" value="" class="input-checkbox checkBoxItem" />
                            </th>
                            <th>Avatar</th>
                            <th>Thông tin thành viên</th>
                            <th>Địa chỉ</th>
                            <th>Tình trạng</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <input type="checkbox" value="" id="checkAll" class="input-checkbox" />
                            </td>
                            <td>
                                <span class="image">
                                    <img src="data:image/jpeg;base64,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"
                                        alt="">
                                </span>
                            </td>
                            <td>
                                <div class="info-item name">Họ và tên</div>
                                <div class="info-item email">Email</div>
                                <div class="info-item Phone">Số điện thoại</div>
                            </td>
                            <td>
                                <div class="address-item name">Họ và tên</div>
                                <div class="address-item email">Email</div>
                                <div class="address-item Phone">Số điện thoại</div>
                            </td>
                            <td class="text-navy">
                                
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
