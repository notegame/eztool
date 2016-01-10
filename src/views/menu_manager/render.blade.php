<div ng-app="menuManagerApp">
    <div id="menu_manager" class="dd menu-manager" ng-controller="menuManagerController as menuManager">

        @if(\Sentinel::hasAccess($permissions['create']))
        <div class="text-right">
            <a href="#modal-create-menu" class="btn btn-primary" data-toggle="modal"><i class="fa fa-plus"></i> Create</a>
        </div>
        @endif

        <ol class="dd-list">
            <li class="dd-item list-close menu-manager__list" data-id="{(menu.id)}" menu-list ng-repeat="menu in menuManager.menus"></li>
        </ol>

        <!-- Small Modal -->
        <div id="modal-create-menu" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h3 class="modal-title"><strong>Create Menu</strong></h3>
                    </div>
                    <div class="modal-body">
                        <form class="form" onsubmit="return false;">
                            <div class="form-group">
                                <label for="create_name">Name</label>
                                <input type="text" id="create_name" ng-model="menuManager.create.name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="create_name">Label</label>
                                <input type="text" id="create_label" ng-model="menuManager.create.label" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="create_url">URL</label>
                                <input type="text" id="create_url" ng-model="menuManager.create.url" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="create_permissions">Permission</label>
                                <select id="create_permissions" ng-model="menuManager.create.permissions" class="select-chosen form-control" data-placeholder="Choose a permission.." multiple>
                                    <option value="{(permission)}" ng-repeat="permission in menuManager.permissions">{(permission)}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="create_attr">Attribute</label>
                                <input type="text" id="create_attr" ng-model="menuManager.create.attr" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="create_icon">Icon</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="{(menuManager.create.icon)}"></i></span>
                                    <input type="text" id="create_icon" ng-model="menuManager.create.icon" class="form-control">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-effect-ripple btn-primary" ng-click="menuManager.createSubmit($event)">Save</button>
                        <button type="button" class="btn btn-effect-ripple btn-danger" data-dismiss="modal" ng-click="menuManager.closeSubmit()">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Small Modal -->
    </div>
</div>

<script type="text/ng-template" id="menu_list.html">
    <div class="dd-handle">
        <div class="pull-left">{(menu.label)} ({(menu.name)})</div>
    </div>
    <a href="javascript:void(0);" class="btn btn-info btn-sm menu-manager__list__btn--collapse" ng-click="menuManager.collapse(menu,$event)" style="top: 0px;position: absolute;right: 6px;padding: 0px 4px;margin-top: 4px;"><i class="fa fa-chevron-down"></i></a>
    
    <div class="menu-manager__list__content">
        <div class="row">
            <div class="col-md-12">
                <form class="form" onsubmit="return false;">
                    <div class="form-group">
                        <label for="menu_name_{(menu.id)}">Name</label>
                        <input type="text" id="menu_name_{(menu.id)}" {{!\Sentinel::hasAccess($permissions['update']) ? 'readonly' : ''}} ng-model="menu.name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="menu_label_{(menu.id)}">Label</label>
                        <input type="text" id="menu_label_{(menu.id)}" {{!\Sentinel::hasAccess($permissions['update']) ? 'readonly' : ''}} ng-model="menu.label" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="menu_url_{(menu.id)}">URL</label>
                        <input type="text" id="menu_url_{(menu.id)}" {{!\Sentinel::hasAccess($permissions['update']) ? 'readonly' : ''}} ng-model="menu.url" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="menu_permissions_{(menu.id)}">Permission</label>
                        <select id="menu_permissions_{(menu.id)}" on-post-repeat-directive {{!\Sentinel::hasAccess($permissions['update']) ? 'readonly' : ''}} ng-model="menu.permissions" class="select-chosen form-control" data-placeholder="Choose a permission.." multiple ng-multiple="true" ng-options="permission for permission in menuManager.permissions" >
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="menu_attr_{(menu.id)}">Attribute</label>
                        <input type="text" id="menu_attr_{(menu.id)}" {{!\Sentinel::hasAccess($permissions['update']) ? 'readonly' : ''}} ng-model="menu.attr" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="menu_icon_{(menu.id)}">Icon</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="{(menu.icon)}"></i></span>
                            <input type="text" id="menu_icon_{(menu.id)}" {{!\Sentinel::hasAccess($permissions['update']) ? 'readonly' : ''}} ng-model="menu.icon" class="form-control">
                        </div>
                        
                    </div>
                    <div class="form-group text-right">
                        @if(\Sentinel::hasAccess($permissions['update']))
                        <button type="button" class="btn btn-effect-ripple btn-primary" ng-click="menuManager.save(menu,$event)">Save</button>
                        @endif
                        @if(\Sentinel::hasAccess($permissions['delete']))
                        <button type="button" class="btn btn-effect-ripple btn-danger" ng-click="menuManager.delete(menu,$event)">Delete</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <ol class="dd-list" ng-if="menu.childs.length>0">
        <li class="dd-item list-close menu-manager__list" data-id="{(menu.id)}" menu-list ng-repeat="menu in menu.childs"></li>
    </ol>
</script>