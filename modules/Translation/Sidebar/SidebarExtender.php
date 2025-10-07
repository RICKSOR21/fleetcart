<?php

namespace Modules\Translation\Sidebar;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\Admin\Sidebar\BaseSidebarExtender;

class SidebarExtender extends BaseSidebarExtender
{
    public function extend(Menu $menu)
    {
        $menu->group(trans('admin::sidebar.system'), function (Group $group) {
            $group->item(trans('admin::sidebar.localization'), function (Item $item) {
                $item->weight(10);
                $item->icon('fa fa-globe');

                $item->item(trans('translation::sidebar.languages'), function (Item $item) {
                    $item->route('admin.languages.index');
                    $item->weight(5);

                    $item->authorize(
                        $this->auth->hasAnyAccess([
                            'admin.languages.index',
                            'admin.languages.add',
                        ])
                    );
                });
            });
        });
    }
}
