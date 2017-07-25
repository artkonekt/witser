<?php
/**
 * Contains the ModuleServiceProvider class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-06-14
 *
 */


namespace Konekt\Stift\Providers;


use Konekt\Concord\BaseBoxServiceProvider;
use Konekt\Stift\Models\Issue;
use Konekt\Stift\Models\IssueType;
use Konekt\Stift\Models\Project;
use Konekt\Stift\Models\Severity;
use Menu;

class ModuleServiceProvider extends BaseBoxServiceProvider
{
    protected $models = [
        Project::class,
        IssueType::class,
        Severity::class,
        Issue::class
    ];

    public function boot()
    {
        parent::boot();

        if ($menu = Menu::get('appshell')) {
            $menu->addItem('issues', __('Issues'), ['route' => 'stift.issue.index'])->data('icon', 'check-circle-u');
        }
    }


}