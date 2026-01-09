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
        if (!defined('PAGE')) return;
        if (empty($project_id)) {
            if (
                str_replace(APP_PATH_WEBROOT_PARENT, '', PAGE_FULL)=='index.php' &&
                isset($_GET['action']) && $_GET['action']==='create') 
            {
                // For "Create new Project" hide "Empty Project" option and default to template
                $this->newProjectPage();

            } else if (PAGE==='manager/control_center.php') {
                $this->checkEnabledForAll();
            }

        } else {
            // Dev projects force single user into owner role (if template has one) - works more flexibly than query string "new" with new project requests
            global $Proj;
            if ($Proj->project['status'] > 0) return;
            $this->userRoleAssignment($project_id);
        
            if (PAGE==='manager/project.php') {
                ?>
                <style type="text/css">
                    .config-instruction { display: none; }
                </style>
                <?php
            }
        }
    }

    protected function newProjectPage() {
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
    }

    protected function userRoleAssignment($project_id): void {
        // do nothing when >1 user or user is in role
        $result = $this->query("select count(*) as n_users, count(role_id) as n_in_role from redcap_user_rights where project_id=? ", [ $project_id ]);
        $row = $result->fetch_assoc();
        $nUsers = $row['n_users'];
        $nInRole = $row['n_in_role'];
        if ($nUsers>1 || $nInRole>0) return;

        $defaultRoles = $this->getSystemSetting('project-owner-role-name');
        $defaultRoles = array_values(array_filter($defaultRoles, function($roleName) {
            return !is_null($roleName) && !empty(trim($roleName)); 
        }));

        foreach ($defaultRoles as $roleName) {
            $result = $this->query("select role_id from redcap_user_roles where project_id=? and role_name=? limit 1", [ $project_id, $roleName ]);
            if ($result->num_rows===0) continue;
            
            $row = $result->fetch_assoc();
            $roleId = $row['role_id'];
            if ($this->moveUserToDefaultRole($roleId)) {
                break;
            }
        }
    }

    protected function moveUserToDefaultRole($defaultRoleId): bool {
        $sql = "update redcap_user_rights set role_id=? where project_id=? and role_id is null limit 1"; // already checked project has only one user - the creator/requestor
        if ($this->query($sql, [ $defaultRoleId, PROJECT_ID ])) {
            \REDCap::logEvent('External Module "'.$this->getModuleName().'": Assign user to default role','role_id = '.$defaultRoleId, sprintf(str_replace('?','%s',$sql), $defaultRoleId, PROJECT_ID));
            return true;
        }
        return false;
    }

    /**
     * checkEnabledForAll()
     * Highlight when module not enabled for all projects
     * @return void
     */
    protected function checkEnabledForAll(): void {
        if (!$this->getSystemSetting('enabled')) {
            ?>
            <style type="text/css">
                .config-instruction { color: red; font-weight: bold; }
            </style>
            <?php
        }
    }

    /**
     * redcap_module_configuration_settings
     * Triggered when the system or project configuration dialog is displayed for a given module.
     * Allows dynamically modify and return the settings that will be displayed.
     * @param string $project_id, $settings
     * @return array
     */
    public function redcap_module_configuration_settings($project_id, $settings) {
        if (empty($project_id)) {
            if (!$this->getSystemSetting('enabled')) {
                foreach ($settings as $si => $sarray) {
                    if ($sarray['key']=='enable-on-all') {
                        $settings[$si]['hidden'] = false;
                        break;
                    }
                }
            }
        }
        return $settings;
    }

    public function redcap_user_rights($project_id) {
        if (!$this->getSystemSetting('hide-custom-rights')) return;
        ?>
        <style type="text/css">
            #addUsersRolesDiv > div:nth-child(3) { display: none; }
            #addUsersRolesDiv > div:nth-child(4) { display: none; }
        </style>
        <?php
    }

    public function redcap_every_page_before_render($project_id) {
        if (defined('PAGE') 
            && PAGE==='UserRights/index.php' 
            && !empty($project_id)
            && $this->getSystemSetting('hide-custom-rights') ) 
        {
            global $lang;
            $lang['rights_162'] = ''; // "Give them custom user rights or assign them to a role."
        }
    }
}