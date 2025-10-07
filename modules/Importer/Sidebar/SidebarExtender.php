<?php

namespace Modules\Importer\Sidebar;

use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Maatwebsite\Sidebar\Group;
use Modules\Admin\Sidebar\BaseSidebarExtender;

class SidebarExtender extends BaseSidebarExtender
{
    public function extend(Menu $menu)
    {
        $menu->group(trans('admin::sidebar.content'), function (Group $group) {
            $group->item(trans('importer::importer.import'), function (Item $item) {
                $item->weight(40);
                $item->icon('fa fa-download');
                $item->route('admin.importer.index');
                $item->authorize(
                    $this->auth->hasAccess('admin.importer.import')
                );
            });
        });
    }
}
