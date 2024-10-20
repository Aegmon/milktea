<aside class="main-sidebar">
	<section class="sidebar">
		<ul class="sidebar-menu">

			<?php
			if ($_SESSION["profile"] == "Administrator") {
				echo '
					<li class="active">
						<a href="home">
							<i class="fa fa-home"></i>
							<span>Home</span>
						</a>
					</li>
				';
			}

			if ($_SESSION["profile"] == "Administrator" || $_SESSION["profile"] == "Employee") {
				echo '
					<li>
						<a href="customers">
							<i class="fa fa-users"></i>
							<span>Customers</span>
						</a>
					</li>
				';
			}

			if ($_SESSION["profile"] == "Administrator" || $_SESSION["profile"] == "Employee") {
				echo '
					<li>
						<a href="sales">
							<i class="fa fa-money"></i>
							<span>Sales</span>
							<span class="pull-right-container">
							</span>
						</a>
					</li>
					<li>
						<a href="create-sale">
							<i class="fa fa-money"></i>
							<span>POS</span>
							<span class="pull-right-container">
							</span>
						</a>
					</li>
					<li>
						<a href="reports">
							<i class="fa fa-file"></i>
							<span>Sales Report</span>
						</a>
					</li>
				';
			}

			if ($_SESSION["profile"] == "Administrator") {
				echo '
					<li class="treeview"> 
						  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-th"></i>
							<span>Inventory Management</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li>
								<a href="categories">
									<i class="fa fa-th"></i>
									<span>Categories</span>
								</a>
							</li>
							<li>
								<a href="products">
									<i class="fa fa-product-hunt"></i>
									<span>Products</span>
								</a>
							</li>
						
							<li>
								<a href="ingredients">
									<i class="fa fa-th"></i>
									<span>Ingredients</span>
								</a>
							</li>
						</ul>
					</li>
					<li>
						<a href="users">
							<i class="fa fa-user"></i>
							<span>User Management</span>
						</a>
					</li>
				';
			}
			?>
		</ul>
	</section>
</aside>

