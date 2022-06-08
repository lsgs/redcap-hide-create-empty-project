<?php
/**
 * REDCap External Module: Hide Create Empty Project
 * - Hides the "Create an empty project (blank slate)" option when creating or requesting a new project thus forcing users to use a template or XML.
 * - When a dev project has a single user not in a role, and the system-specified default role is present, the user will be added to this role.
 * @author Luke Stevens, Murdoch Children's Research Institute
 */
namespace MCRI\HideCreateEmptyProject;

use ExternalModules\AbstractExternalModule;

class HideCreateEmptyProject extends AbstractExternalModule
{
    public function redcap_every_page_top($project_id) {
        if (str_replace(APP_PATH_WEBROOT_PARENT, '', PAGE_FULL)=='index.php' &&
                isset($_GET['action']) && $_GET['action']==='create') {
            // For "Create new Project" hide "Empty Project" option and default to template
            ?>
            <script type='text/javascript'>
                $(document).ready(function() {
                    $('#project_template_radio0').closest('div').hide(); 
                    setTimeout(function(){
                        $('#project_template_radio1').val([1]).change();
                    }, 200);
                });
            </script>
            <?php
        } else if (isset($_GET['pid']) && $_GET['pid']>0) {
            // Dev projects force single user into owner role (if template has one) - works more flexibly than query string "new" with new project requests
            global $Proj;
            if ($Proj->project['status'] > 0) return;
            $defaultRole = $this->getSystemSetting('project-owner-role-name');
            if ($defaultRole!=='') {
                $defaultRoleId = 0;
                $r1 = $this->query("select role_id from redcap_user_roles where project_id=? and role_name=? limit 1", [ $project_id, $defaultRole ]);
                if ($r1->num_rows > 0) {
                    while($row = $r1->fetch_assoc()){
                        $defaultRoleId = $row['role_id'];
                    }
                }
                $r2 = $this->query("select if(role_id is null, 0, 1) as in_role from redcap_user_rights where project_id=? ", [ $project_id ]);
                $nInRole = 0;
                $nUsers = $r2->num_rows;
                while($row = $r2->fetch_assoc()){
                    $nInRole += $row['in_role'];
                }
                // if default role present and project has single user not in that role, then add them
                if ($defaultRoleId > 0 && $nUsers==1 && $nInRole==0) {
                    $this->moveUserToDefaultRole($defaultRoleId);
                }
            }
        }
    }

    protected function moveUserToDefaultRole($defaultRoleId) {
        $sql = "update redcap_user_rights set role_id=? where project_id=? and role_id is null limit 1"; // already checked project has only one user - the creator/requestor
        if ($this->query($sql, [ $defaultRoleId, PROJECT_ID ])) {
            \REDCap::logEvent('External Module "'.$this->getModuleName().'": Assign user to default role','role_id = '.$defaultRoleId, sprintf(str_replace('?','%s',$sql), $defaultRoleId, PROJECT_ID));
        }
    }
}
