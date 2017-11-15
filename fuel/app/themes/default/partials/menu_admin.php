<div class="header">
        <div class="navigation-bar">
            <nav class="navbar navbar-default">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"
                        aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#/"><?php echo Asset::img('logo.png');?></a>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <li class="active"><a href="#">USER</a></li>
                        <li><a ng-click="showOrder(<?php echo Auth::get('id');?>)">ORDER</a></li>
                        <li><a href="<?php echo Uri::create('/api/auth/logout'); ?>">LOGOUT</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</div>