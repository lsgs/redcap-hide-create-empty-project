<?php
/**
 * REDCap External Module: Hide Create Empty Project
 * Hides the "Create an empty project (blank slate)" option when creating or
 * requesting a new project.
 * @author Luke Stevens, Murdoch Children's Research Institute
 */
namespace MCRI\HideCreateEmptyProject;

use ExternalModules\AbstractExternalModule;
use REDCap;

class HideCreateEmptyProject extends AbstractExternalModule
{
        public function redcap_every_page_top($project_id) {
                if (PAGE === 'redcap/index.php' || PAGE === 'index.php?action=create') { 
                        // For "Create new Project" hide "Empty Project" option and default to template
                        if (isset($_GET['action']) && $_GET['action']==='create') {
                            ?>
                            <script type='text/javascript'>
                                $(document).ready(function() {
                                    $('input[name="project_template_radio"][value="0"]').closest('div').hide(); 
//                                    $(window).on('load', function() {
                                        setTimeout(function() {
                                            $('input[name="project_template_radio"]').val([1]).change().click();
                                        }, 1000);
//                                     });
                                });
                            </script>
                            <?php
                        }
                } else if (PAGE === 'ProjectSetup/index.php' && isset($_GET['msg']) && $_GET['msg']==='newproject') {
                        // When project created, add project users to "Project Owner" role (if template has one)
                        $defaultRole = $this->getSystemSetting('project-owner-role-name');
                        if ($defaultRole!=='') {
                                $sql = "select role_id from redcap_user_roles where project_id=".db_escape(PROJECT_ID)." and role_name='".db_escape($defaultRole)."' limit 1";
                                $r = db_query($sql);
                                if ($r->num_rows > 0) {
                                        $roleId = $r->fetch_assoc()['role_id'];
                                        $sql = "update redcap_user_rights set role_id=".db_escape($roleId)." where project_id=".db_escape(PROJECT_ID)." and role_id is null";
                                        $q = db_query($sql);

                                        if (db_affected_rows($q) > 0) {
                                                REDCap::logEvent('External Module "'.$this->getModuleName().'": Assign default user to default role','role_id = '.$roleId, $sql);
                                        }
                                }
                        }
                }
        }
}