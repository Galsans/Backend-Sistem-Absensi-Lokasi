<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NavigationGroup extends Model
{
    protected $guarded = [];

    /**
     * Get the role that owns the NavigationGroup
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function navigationMenu()
    {
        return $this->belongsTo(navigationMenu::class, 'navigation_menu_id');
    }
}
