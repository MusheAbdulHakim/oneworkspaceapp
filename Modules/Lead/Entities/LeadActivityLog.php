<?php

namespace Modules\Lead\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeadActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','lead_id','log_type','remark'
    ];

    protected static function newFactory()
    {
        return \Modules\Lead\Database\factories\LeadActivityLogFactory::new();
    }
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }


    public function getLeadRemark()
    {
        $remark = json_decode($this->remark, true);
        if($remark)
        {
            $user = $this->user;

            if($user)
            {
                $user_name = $user->name;
            }
            else
            {
                $user_name = '';
            }

            if($this->log_type == 'Upload File')
            {
                return $user_name . ' ' . __('Upload new file') . ' <b>' . $remark['file_name'] . '</b>';
            }
            elseif($this->log_type == 'Add Product')
            {
                return $user_name . ' ' . __('Add new Products') . " <b>" . $remark['title'] . "</b>";
            }
            elseif($this->log_type == 'Update Sources')
            {
                return $user_name . ' ' . __('Update Sources');
            }
            elseif($this->log_type == 'Create Lead Call')
            {
                return $user_name . ' ' . __('Create new Lead Call');
            }
            elseif($this->log_type == 'Create Lead Email')
            {
                return $user_name . ' ' . __('Create new Lead Email');
            }
            elseif($this->log_type == 'Move')
            {
                return $user_name . " " . __('Moved the deal') . " <b>" . $remark['title'] . "</b> " . __('from') . " " . __(ucwords($remark['old_status'])) . " " . __('to') . " " . __(ucwords($remark['new_status']));
            }
            elseif($this->log_type == 'Create Task')
            {
                return $user_name . ' ' . __('Create new Task') . " <b>" . $remark['title'] . "</b>";
            }
        }
        else
        {
            return $this->remark;
        }
    }

    public function logIcon()
    {
        $type = $this->log_type;
        $icon = '';

        if(!empty($type))
        {
            if($type == 'Move')
            {
                $icon = 'fa-arrows-alt';
            }
            elseif($type == 'Add Product')
            {
                $icon = 'fa-dolly';
            }
            elseif($type == 'Upload File')
            {
                $icon = 'fa-file-alt';
            }
            elseif($type == 'Update Sources')
            {
                $icon = 'fa-pen';
            }
            elseif($type == 'Create Lead Call')
            {
                $icon = 'fa-phone';
            }
            elseif($type == 'Create Lead Email')
            {
                $icon = 'fa-envelope';
            }
            elseif($type == 'Create Task')
            {
                $icon = 'fa-tasks';
            }
        }

        return $icon;
    }
}
