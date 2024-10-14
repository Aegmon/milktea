<?php

if ($_SESSION["profile"] == "Seller") {
    echo '<script>
        window.location = "home";
    </script>';
    return;
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Attribute Management
        </h1>
        <ol class="breadcrumb">
            <li><a href="home"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <button class="btn btn-success" data-toggle="modal" data-target="#addAttributes"> <i class="fa fa-plus"></i> Add Attributes</button>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-hover table-striped dt-responsive tables" width="100%">
                    <thead>
                        <tr>
                            <th style="width:10px">#</th>
                            <th>Attribute</th>
                            <th>Symbol</th>
                            <th>Actions</th>
                        </tr> 
                    </thead>
                    <tbody>
                        <?php
                        $item = null; 
                        $value = null;

                        // Adjust the controller method to fetch attributes
                        $attributes = ControllerAttributes::ctrShowAttributes($item, $value);

                        foreach ($attributes as $key => $value) {
                            echo '<tr>
                                <td>' . ($key + 1) . '</td>
                                <td class="text-uppercase">' . $value['attributes'] . '</td>
                                <td>' . $value['symbol'] . '</td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-primary btnEditAttribute" idAttribute="' . $value["id"] . '" data-toggle="modal" data-target="#editAttributes"><i class="fa fa-pencil"></i></button>
                                        <button class="btn btn-danger btnDeleteAttribute" idAttribute="' . $value["id"] . '"><i class="fa fa-trash"></i></button>
                                    </div>  
                                </td>
                            </tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.box -->
    </section>
    <!-- /.content -->
</div>

<!--=====================================
=            module add Attributes            =
======================================-->
<!-- Modal -->
<div id="addAttributes" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form role="form" method="POST">
                <div class="modal-header" style="background: #DD4B39; color: #fff">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Attributes</h4>
                </div>
                <div class="modal-body">
                    <div class="box-body">
                        <!-- Input for attribute name -->
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-th"></i></span>
                                <input class="form-control input-lg" type="text" name="newAttribute" placeholder="Add Attribute" required>
                            </div>
                        </div>
                        <!-- Input for symbol -->
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                                <input class="form-control input-lg" type="text" name="newSymbol" placeholder="Add Symbol" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save Attribute</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$createAttribute = new ControllerAttributes();
$createAttribute->ctrCreateAttribute();
?>

<!--=====================================
=            module edit Attributes            =
======================================-->
<!-- Modal -->
<div id="editAttributes" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form role="form" method="POST">
                <div class="modal-header" style="background: #DD4B39; color: #fff">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit Attributes</h4>
                </div>
                <div class="modal-body">
                    <div class="box-body">
                        <!-- Input for attribute name -->
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-th"></i></span>
                                <input class="form-control input-lg" type="text" id="editAttribute" name="editAttribute" required>
                                <input type="hidden" name="idAttribute" id="idAttribute" required>
                            </div>
                        </div>
                        <!-- Input for symbol -->
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                                <input class="form-control input-lg" type="text" id="editSymbol" name="editSymbol" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save Changes</button>
                </div>

                <?php
                $editAttribute = new ControllerAttributes();
                $editAttribute->ctrEditAttribute();
                ?>
            </form>
        </div>
    </div>
</div>

<?php
$deleteAttribute = new ControllerAttributes();
$deleteAttribute->ctrDeleteAttribute();
?>
