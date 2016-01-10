<?php $lv++; ?>
@foreach($permission_list as $permission)
    <tr class="{{ $lv==0 ? "permission-main" : "permission-childs"}}">
        <td class="table--ez-acl__td--permission-name">
        @for ($i = 0; $i < $lv; $i++)
        	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        @endfor
        <?php
        $permission_name = isset($parent) ? $parent."." : '';
        $permission_name .= $permission->name;
        ?>
        @if($lv>0)
        - {{$permission_name}}
        @else
        <strong>{{$permission_name}}</strong>
        @endif
        </td>
        @foreach($roles as $role)
            <td class="table--ez-acl__td--role-check">{(ACLManager.permission.name)}<label class=""><input type="checkbox" {{!\Sentinel::hasAccess($permissions['update']) ? 'disabled' : ''}} {{@$role->permissions[$permission_name]==1 ? 'checked' : ''}} ng-click="ACLManager.permissionClick($event)" name="role[{{$role->id}}][{{$permission_name}}]" value="1" data-permission-id="{{$permission->id}}" data-permission-slug="{{$permission_name}}" {{ isset($main) ? 'data-permission-main-id='.$main->id.' data-permission-main-slug='.$main->name : ''}} data-role-id="{{$role->id}}" data-role-slug="{{$role->slug}}" ><span></span></label></td>
        @endforeach
    </tr>
    @if($permission->childs)
    	@include('eztool::acl_manager.permission_list', [
        'permission_list'=>$permission->childs,
        'main'=>isset($main) ? $main : $permission,
        'lv'=> $lv,
        'parent'=>trim(@$parent.".".$permission->name,'.')
        ])
    @endif

    @if($lv==0)
    <tr>
        <td colspan="{{count($roles)+1}}">&nbsp;</td>
    </tr>
    @endif

@endforeach