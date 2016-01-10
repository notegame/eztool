<div ng-app="permissionEditorApp">
    <div id="permission_editor" class="dd permission-editor" ng-controller="permissionEditorController as permEditor">

        @if(\Sentinel::hasAccess($permissions['create']))
        <div class="text-right">
            <a href="#modal-create-permission" class="btn btn-primary" data-toggle="modal"><i class="fa fa-plus"></i> Create</a>
        </div>
        @endif

        <ol class="dd-list">
            <li class="dd-item list-close permission-editor__list" data-id="{(permission.id)}" permission-list ng-repeat="permission in permEditor.permissions"></li>
        </ol>

        <!-- Small Modal -->
        <div id="modal-create-permission" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h3 class="modal-title"><strong>Create Permission</strong></h3>
                    </div>
                    <div class="modal-body">
                        <form class="form" onsubmit="return false;">
                            <div class="form-group">
                                <label for="create_name">Name</label>
                                <input type="text" id="create_name" ng-model="permEditor.create.name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="create_desc">Description</label>
                                <textarea id="create_desc" ng-model="permEditor.create.desc" class="form-control"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-effect-ripple btn-primary" ng-click="permEditor.createSubmit($event)">Save</button>
                        <button type="button" class="btn btn-effect-ripple btn-danger" data-dismiss="modal" ng-click="permEditor.closeSubmit()">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Small Modal -->
    </div>
</div>

<script type="text/ng-template" id="permission_list.html">
    <div class="dd-handle">
        <div class="pull-left">{(permission.name)}</div>
    </div>
    <a href="javascript:void(0);" class="btn btn-info btn-sm permission-editor__list__btn--collapse" ng-click="permEditor.collapse(permission,$event)" style="top: 0px;position: absolute;right: 6px;padding: 0px 4px;margin-top: 4px;"><i class="fa fa-chevron-down"></i></a>
    
    <div class="permission-editor__list__content">
        <div class="row">
            <div class="col-md-12">
                <form class="form" onsubmit="return false;">
                    <div class="form-group">
                        <label for="permission_name_{(permission.id)}">Name</label>
                        <input type="text" id="permission_name_{(permission.id)}" {{!\Sentinel::hasAccess($permissions['update']) ? 'readonly' : ''}} ng-model="permission.name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="permission_desc_{(permission.id)}">Description</label>
                        <input type="text" id="permission_desc_{(permission.id)}" {{!\Sentinel::hasAccess($permissions['update']) ? 'readonly' : ''}} ng-model="permission.desc" class="form-control">
                    </div>
                    <div class="form-group text-right">
                        @if(\Sentinel::hasAccess($permissions['update']))
                        <button type="button" class="btn btn-effect-ripple btn-primary" ng-click="permEditor.save(permission,$event)">Save</button>
                        @endif
                        @if(\Sentinel::hasAccess($permissions['delete']))
                        <button type="button" class="btn btn-effect-ripple btn-danger" ng-click="permEditor.delete(permission,$event)">Delete</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <ol class="dd-list" ng-if="permission.childs.length>0">
        <li class="dd-item list-close permission-editor__list" data-id="{(permission.id)}" permission-list ng-repeat="permission in permission.childs"></li>
    </ol>
</script>