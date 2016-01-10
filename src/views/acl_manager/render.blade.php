<div id="ACLManagerApp" ng-app="ACLManagerApp">
    <div ng-controller="ACLManagerController as ACLManager">
    
        @if(\Sentinel::hasAccess($permissions['update']))
        <div class="block--ez-action-button">
            <button class="btn btn-primary" ng-click="ACLManager.saveSubmit()"><i class="fa fa-check"></i> Save</button>
        </div>
        @endif

        <form id="ez_acl_manager">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <table class="table--ez-acl">
                <thead>
                    <tr>
                        <th class="table--ez-acl__th--permission-name"></th>
                        @foreach($roles as $role)
                            <th class="table--ez-acl__th--role-name">{{$role->name}}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        @foreach($roles as $role)
                            <td class="table--ez-acl__td--role-checkall"><label class=""><input type="checkbox" {{$permissions['update']==false ? 'disabled' : ''}} value="1" ng-click="ACLManager.permissionClick($event)" data-role-id="{{$role->id}}" data-role-slug="{{$role->slug}}"><span></span></label></td>
                        @endforeach
                    </tr>
                    @include('eztool::acl_manager.permission_list', ['permissions'=>$permissions, 'lv'=>-1])
                </tbody>
            </table>
        </form>
    </div>
</div>