<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Classlist extends CI_Controller {

    public
            function __construct() {
        parent:: __construct();
        $this->load->helper(array('form', 'url', 'html', 'path', 'form', 'cookie'));
        $this->load->library(array('email', 'session', 'form_validation', 'pagination', 'parser', 'encrypt'));
        error_reporting(E_ALL ^ E_NOTICE);
        $this->load->library("layouts");
        $this->load->model(array('admin_model', 'common_model', 'emailtemplate_model', 'sms_model'));
        $this->load->helper('language');
        $this->lang->load('statictext', 'admin');
    }

    /*     * *********************************************************************
     * * Function name : classlist
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for classlist
     * * Date : 23 JANUARY 2018
     * * ********************************************************************* */

    public function index() {
        $this->admin_model->authCheck('admin', 'view_data');
        $this->admin_model->get_permission_type($data);
        $data['error'] = '';

        if ($this->input->get('searchValue')):
            $sValue              = $this->input->get('searchValue');
            $whereCon['like']    = "(cls.class_name LIKE '%" . $sValue . "%' OR cls.class_short_name LIKE '%" . $sValue . "%')";
            $data['searchValue'] = $sValue;
        else:
            $whereCon['like']    = "";
            $data['searchValue'] = '';
        endif;

        $whereCon['where'] = "cls.franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
												 AND cls.school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
												 AND cls.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
												 AND cls.board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'";
        $shortField        = 'cls.class_name ASC';

        $this->load->library('pagination');
        $config['base_url']   = $this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/index';
        $this->session->set_userdata('classListAdminData', currentFullUrl());
        $qStringdata          = explode('?', currentFullUrl());
        $config['suffix']     = $qStringdata[1] ? '?' . $qStringdata[1] : '';
        $tblName              = 'classes as cls';
        $con                  = '';
        $config['total_rows'] = $this->admin_model->SelectClassListData('count', $tblName, $whereCon, $shortField, '0', '0');

        if ($this->input->get('showLength') == 'All'):
            $config['per_page'] = $config['total_rows'];
            $data['perpage']    = $this->input->get('showLength');
        elseif ($this->input->get('showLength')):
            $config['per_page'] = $this->input->get('showLength');
            $data['perpage']    = $this->input->get('showLength');
        else:
            $config['per_page'] = SHOW_NO_OF_DATA;
            $data['perpage']    = SHOW_NO_OF_DATA;
        endif;

        $config['uri_segment'] = getUrlSegment();
        $this->pagination->initialize($config);

        if ($this->uri->segment(getUrlSegment())):
            $page = $this->uri->segment(getUrlSegment());
        else:
            $page = 0;
        endif;

        $data['forAction'] = $config['base_url'];
        if ($config['total_rows']):
            $first               = ($page) + 1;
            $data['first']       = $first;
            $last                = (($page) + $data['perpage']) > $config['total_rows'] ? $config['total_rows'] : (($page) + $data['perpage']);
            $data['noOfContent'] = 'Showing ' . $first . '-' . $last . ' of ' . $config['total_rows'] . ' items';
        else:
            $data['first']       = 1;
            $data['noOfContent'] = '';
        endif;

        $data['ALLDATA'] = $this->admin_model->SelectClassListData('data', $tblName, $whereCon, $shortField, $config['per_page'], $page);

        $this->layouts->set_title('Manage class details');
        $this->layouts->admin_view('classlist/index', array(), $data);
    }

// END OF FUNCTION

    /*     * *********************************************************************
     * * Function name : addeditdata
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for add edit data
     * * Date : 23 JANUARY 2018
     * * ********************************************************************* */

    public function addeditdata($editid = '') {
        $data['error']      = '';
        $asubQuery          = "SELECT encrypt_id,subject_name FROM sms_subject WHERE franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "'
										 AND school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
										 AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
										 AND board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'
										 AND status = 'Y'";
        $data['ALLSUBDATA'] = $this->common_model->get_data_by_query('multiple', $asubQuery);

        if ($editid):
            $this->admin_model->authCheck('admin', 'edit_data');
            $data['EDITDATA'] = $this->common_model->get_data_by_encryptId('classes', $editid);

            $periodQuery         = "SELECT encrypt_id,class_period_name as period_name,class_period_short_name,class_period_start_time as start_time,class_period_end_time as end_time,class_period_duration as duration
										 FROM sms_class_period WHERE class_id = '" . $editid . "'";
            $data['SDETAILDATA'] = $this->common_model->get_data_by_query('multiple', $periodQuery);


            $subQuery            = "SELECT sc.subject_id ,s.subject_name FROM sms_class_subject as sc left join   sms_subject  as s on s.encrypt_id   = sc.subject_id
                                    
										 WHERE  sc.class_id  =  '" . $editid . "' and
                                                                                 sc.franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
										 AND sc.school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
										 AND sc.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "' 
										 AND sc.board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'   AND sc.status = 'Y'";
            $data['SUBJECTDATA'] = $this->common_model->get_ids_in_array('subject_id', $subQuery);



        else:
            $this->admin_model->authCheck('admin', 'add_data');
        endif;

        if ($this->input->post('SaveChanges')):


            $error = 'NO';
            $this->form_validation->set_rules('class_name', 'Class name', 'trim|required');
            $this->form_validation->set_rules('class_short_name', 'Class short name', 'trim|required');
            if ($data['ALLSUBDATA']):
                $this->form_validation->set_rules('subject_name[]', 'Subject', 'trim|required');
            endif;
            $serror           = 0;
            $TotalPeriodCount = $this->input->post('TotalPeriodCount');
            if ($TotalPeriodCount):
                for ($i = 1; $i <= $TotalPeriodCount; $i++):
                    $this->form_validation->set_rules('period_id_' . $i, 'lang:Period Id', 'trim');
                    $this->form_validation->set_rules('period_name_' . $i, 'lang:Period Name', 'trim');
                    $this->form_validation->set_rules('duration_' . $i, 'lang:Duration', 'trim');
                    $this->form_validation->set_rules('start_time_' . $i, 'lang:Start Time', 'trim');
                    $this->form_validation->set_rules('end_time_' . $i, 'lang:End Time', 'trim');
                    if ($this->input->post('period_name_' . $i) && $this->input->post('start_time_' . $i) && $this->input->post('end_time_' . $i)):
                        $serror = '1';
                    endif;
                    $data['period_id_' . $i]   = $this->input->post('period_id_' . $i);
                    $data['period_name_' . $i] = $this->input->post('period_name_' . $i);
                    $data['duration_' . $i]    = $this->input->post('duration_' . $i);
                    $data['start_time_' . $i]  = $this->input->post('start_time_' . $i);
                    $data['end_time_' . $i]    = $this->input->post('end_time_' . $i);
                endfor;
                $this->form_validation->set_rules('TotalPeriodCount', 'lang:Total Period', 'trim');
            endif;
            $data['suberror'] = 'Please fill atleast one period details.';
            if ($serror == 0):
                $error = 'YES';
            endif;

            if ($this->form_validation->run() && $error == 'NO'):

                $param['class_name']       = addslashes($this->input->post('class_name'));
                $param['class_short_name'] = addslashes($this->input->post('class_short_name'));
                $param['no_of_period']     = addslashes($this->input->post('TotalPeriod'));

                if ($this->input->post('CurrentDataID') == ''):
                    $param['franchise_id']  = $this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
                    $param['school_id']     = $this->session->userdata('SMS_ADMIN_SCHOOL_ID');
                    $param['branch_id']     = $this->session->userdata('SMS_ADMIN_BRANCH_ID');
                    $param['board_id']      = $this->session->userdata('SMS_ADMIN_BOARD_ID');
                    $param['creation_date'] = currentDateTime();
                    $param['created_by']    = $this->session->userdata('SMS_ADMIN_ID');
                    $param['session_year']  = CURRENT_SESSION;
                    $param['status']        = 'Y';
                    $alastInsertId          = $this->common_model->add_data('classes', $param);

                    $Uparam['encrypt_id'] = manojEncript($alastInsertId);
                    $Uwhere['class_id']   = $alastInsertId;
                    $this->common_model->edit_data_by_multiple_cond('classes', $Uparam, $Uwhere);
                    $this->session->set_flashdata('alert_success', lang('addsuccess'));

                    $classid = $Uparam['encrypt_id'];


                    //add subject
                    if ($data['ALLSUBDATA']):

                        $subjectName = $this->input->post('subject_name');
                        if ($subjectName):
                            foreach ($subjectName as $value):

                                $params['subject_id'] = $value;
                                $params['class_id']   = $classid;


                                $params['franchise_id']  = $this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
                                $params['school_id']     = $this->session->userdata('SMS_ADMIN_SCHOOL_ID');
                                $params['branch_id']     = $this->session->userdata('SMS_ADMIN_BRANCH_ID');
                                $params['board_id']      = $this->session->userdata('SMS_ADMIN_BOARD_ID');
                                $params['creation_date'] = currentDateTime();
                                $params['created_by']    = $this->session->userdata('SMS_ADMIN_ID');
                                $params['status']        = 'Y';
                                $params['session_year']  = CURRENT_SESSION;
                                $salastInsertId          = $this->common_model->add_data('sms_class_subject', $params);

                                $sUparam['encrypt_id']       = manojEncript($salastInsertId);
                                $sUwhere['class_subject_id'] = $salastInsertId;
                                $this->common_model->edit_data_by_multiple_cond('sms_class_subject', $sUparam, $sUwhere);
                            endforeach;
                        endif;

                    endif;



                    if ($TotalPeriodCount):
                        for ($i = 1; $i <= $TotalPeriodCount; $i++):
                            if ($this->input->post('period_name_' . $i) && $this->input->post('start_time_' . $i) && $this->input->post('end_time_' . $i)):
                                $CPparams['class_id']                = $classid;
                                $CPparams['class_period_name']       = addslashes($this->input->post('period_name_' . $i));
                                $CPparams['class_period_duration']   = $this->input->post('duration_' . $i) ? addslashes($this->input->post('duration_' . $i)) : (((strtotime(addslashes($this->input->post('end_time_' . $i))) - strtotime(addslashes($this->input->post('start_time_' . $i)))) / 60));
                                $CPparams['class_period_start_time'] = addslashes($this->input->post('start_time_' . $i));
                                $CPparams['class_period_end_time']   = addslashes($this->input->post('end_time_' . $i));
                                $CPparams['session_year']            = CURRENT_SESSION;
                                $CPlastInsertId                      = $this->common_model->add_data('class_period', $CPparams);

                                $CPUparam['encrypt_id']      = manojEncript($CPlastInsertId);
                                $CPUwhere['class_period_id'] = $CPlastInsertId;
                                $this->common_model->edit_data_by_multiple_cond('class_period', $CPUparam, $CPUwhere);
                            endif;
                        endfor;
                    endif;

                else:
                    $classid              = $this->input->post('CurrentDataID');
                    $param['update_date'] = currentDateTime();
                    $param['updated_by']  = $this->session->userdata('SMS_ADMIN_ID');
                    $this->common_model->edit_data('classes', $param, $classid);
                    $this->session->set_flashdata('alert_success', lang('updatesuccess'));

                    //update subject

                    if ($data['ALLSUBDATA']):


                        $subdel      = array();
                        $subjectName = $this->input->post('subject_name');
                        foreach ($subjectName as $value):

                            if (in_array($value, $data['SUBJECTDATA'])):
                                array_push($subdel, $value);
                                
                            else:
                                $aparams['subject_id'] = $value;
                                $aparams['class_id']   = $classid;


                                $aparams['franchise_id']  = $this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
                                $aparams['school_id']     = $this->session->userdata('SMS_ADMIN_SCHOOL_ID');
                                $aparams['branch_id']     = $this->session->userdata('SMS_ADMIN_BRANCH_ID');
                                $aparams['board_id']      = $this->session->userdata('SMS_ADMIN_BOARD_ID');
                                $aparams['creation_date'] = currentDateTime();
                                $aparams['created_by']    = $this->session->userdata('SMS_ADMIN_ID');
                                $aparams['status']        = 'Y';
                                $aparams['session_year']  = CURRENT_SESSION;
                                $aalastInsertId           = $this->common_model->add_data('sms_class_subject', $aparams);

                                $aUparam['encrypt_id']       = manojEncript($aalastInsertId);
                                $aUwhere['class_subject_id'] = $aalastInsertId;
                                $this->common_model->edit_data_by_multiple_cond('sms_class_subject', $aUparam, $aUwhere);
                            endif;
                        endforeach;


                        foreach ($data['SUBJECTDATA'] as $SUBJECTDATA):
                            if (!in_array($SUBJECTDATA, $subdel)):

                                $Dwhere['franchise_id'] = $this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
                                $Dwhere['school_id']    = $this->session->userdata('SMS_ADMIN_SCHOOL_ID');
                                $Dwhere['branch_id']    = $this->session->userdata('SMS_ADMIN_BRANCH_ID');
                                $Dwhere['board_id']     = $this->session->userdata('SMS_ADMIN_BOARD_ID');
                                $Dwhere['class_id']     = $classid;
                                $Dwhere['subject_id']   = $SUBJECTDATA;

                                $this->common_model->delete_by_multiple_cond('sms_class_subject', $Dwhere);
                            endif;
                        endforeach;


                    endif;



                    $perioddataarray = array();
                    if ($TotalPeriodCount):
                        for ($i = 1; $i <= $TotalPeriodCount; $i++):
                            if ($this->input->post('period_name_' . $i) && $this->input->post('start_time_' . $i) && $this->input->post('end_time_' . $i)):
                                if ($this->input->post('period_id_' . $i)):
                                    $periodid = $this->input->post('period_id_' . $i);

                                    $CPUparam['class_period_name']       = addslashes($this->input->post('period_name_' . $i));
                                    $CPUparam['class_period_duration']   = $this->input->post('duration_' . $i) ? addslashes($this->input->post('duration_' . $i)) : (((strtotime(addslashes($this->input->post('end_time_' . $i))) - strtotime(addslashes($this->input->post('start_time_' . $i)))) / 60));
                                    $CPUparam['class_period_start_time'] = addslashes($this->input->post('start_time_' . $i));
                                    $CPUparam['class_period_end_time']   = addslashes($this->input->post('end_time_' . $i));

                                    $CPUwhere['encrypt_id'] = $periodid;
                                    $CPUwhere['class_id']   = $classid;
                                    $this->common_model->edit_data_by_multiple_cond('class_period', $CPUparam, $CPUwhere);

                                    array_push($perioddataarray, $periodid);
                                else:
                                    $UCPparams['class_id']                = $classid;
                                    $UCPparams['class_period_name']       = addslashes($this->input->post('period_name_' . $i));
                                    $UCPparams['class_period_duration']   = $this->input->post('duration_' . $i) ? addslashes($this->input->post('duration_' . $i)) : (((strtotime(addslashes($this->input->post('end_time_' . $i))) - strtotime(addslashes($this->input->post('start_time_' . $i)))) / 60));
                                    $UCPparams['class_period_start_time'] = addslashes($this->input->post('start_time_' . $i));
                                    $UCPparams['class_period_end_time']   = addslashes($this->input->post('end_time_' . $i));

                                    $UCPlastInsertId = $this->common_model->add_data('class_period', $UCPparams);

                                    $UCPUparam['encrypt_id']      = manojEncript($UCPlastInsertId);
                                    $UCPUwhere['class_period_id'] = $UCPlastInsertId;
                                    $this->common_model->edit_data_by_multiple_cond('class_period', $UCPUparam, $UCPUwhere);
                                endif;
                            endif;
                        endfor;
                        if ($data['SDETAILDATA'] <> "" && count($perioddataarray) > 0):
                            foreach ($data['SDETAILDATA'] as $SDETAILINFO):
                                if (!in_array($SDETAILINFO['encrypt_id'], $perioddataarray)):
                                    $this->common_model->delete_data('class_period', $SDETAILINFO['encrypt_id']);
                                endif;
                            endforeach;
                        endif;
                    endif;

                endif;

                redirect(correctLink('classListAdminData', $this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/index'));
            endif;
        endif;

        $this->layouts->set_title('Edit class details');
        $this->layouts->admin_view('classlist/addeditdata', array(), $data);
    }

// END OF FUNCTION	

    /*     * *********************************************************************
     * * Function name : changestatus
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for change status
     * * Date : 23 JANUARY 2018
     * ********************************************************************** */

    function changestatus($changeStatusId = '', $statusType = '') {
        $this->admin_model->authCheck('admin', 'edit_data');

        $param['status'] = $statusType;
        $this->common_model->edit_data('classes', $param, $changeStatusId);

        $this->session->set_flashdata('alert_success', lang('statussuccess'));

        redirect(correctLink('classListAdminData', $this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/index'));
    }

    /*     * *********************************************************************
     * * Function name : syllabus
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for add edit data
     * * Date : 16 JANUARY 2018
     * * ********************************************************************* */

    public function syllabus($editid = '', $subjectid = '') {
        $data['error']    = '';
        $data['class_id'] = $editid;

        $subjQuery        = "SELECT sc.subject_id ,s.subject_name FROM sms_class_subject as sc left join   sms_subject  as s on s.encrypt_id   = sc.subject_id
                                    
										 WHERE  sc.class_id  =  '" . $editid . "' and
                                                                                 sc.franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
										 AND sc.school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
										 AND sc.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "' 
										 AND sc.board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'   AND sc.status = 'Y'AND s.status = 'Y'";
        $data['SUBDDATA'] = $this->common_model->get_data_by_query('multiple', $subjQuery);



        $class   = $this->common_model->get_data_by_encryptId('classes', $editid);
        $subject = $this->common_model->get_data_by_encryptId('subject', $subjectid);
        if ($subject):
            $data['subject_id'] = $subjectid;

        else:
            $subject            = $this->common_model->get_data_by_encryptId('subject', $data['SUBDDATA']['0']['subject_id']);
            $data['subject_id'] = $data['SUBDDATA']['0']['subject_id'];

        endif;
        $data['subject_name'] = $subject['subject_name'];
        $data['class_name']   = $class['class_name'];

        if ($editid):
            $this->admin_model->authCheck('admin', 'edit_data');

            $sylQuery         = "SELECT cs.syllabus,cs.encrypt_id,cs.class_id,cs.subject_id ,c.class_name ,s.subject_name FROM `sms_class_syllabus`   AS cs
LEFT JOIN `sms_classes` AS c  ON  c.encrypt_id  = cs.class_id 
LEFT JOIN `sms_subject` AS s  ON  s.encrypt_id  = cs.subject_id  WHERE cs.subject_id = '" . $data['subject_id'] . "'  AND cs.class_id = '" . $editid . "' AND s.status = 'Y'";
            $data['EDITDATA'] = $this->common_model->get_data_by_query('single', $sylQuery);
         

        else:
            $this->admin_model->authCheck('admin', 'add_data');
        endif;

        if ($this->input->post('SaveChanges')):


            if ($data['subject_id'] and $data['class_id']):
                $error = 'NO';
            else :

                $error = 'YES';

            endif;
            
            if ($error == 'NO'):

                $param['subject_id'] = $data['subject_id'];
                $param['class_id']   = $data['class_id'];
                $param['syllabus']   = addslashes($this->input->post('syllabus'));

                if ($this->input->post('CurrentDataID') == ''):
                    $param['franchise_id']  = $this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
                    $param['school_id']     = $this->session->userdata('SMS_ADMIN_SCHOOL_ID');
                    $param['branch_id']     = $this->session->userdata('SMS_ADMIN_BRANCH_ID');
                    $param['board_id']      = $this->session->userdata('SMS_ADMIN_BOARD_ID');
                    $param['creation_date'] = currentDateTime();
                    $param['created_by']    = $this->session->userdata('SMS_ADMIN_ID');
                    $param['status']        = 'Y';
                    $param['session_year']  = CURRENT_SESSION;
                    $alastInsertId          = $this->common_model->add_data('class_syllabus', $param);

                    $Uparam['encrypt_id']        = manojEncript($alastInsertId);
                    $Uwhere['class_syllabus_id'] = $alastInsertId;
                    $this->common_model->edit_data_by_multiple_cond('class_syllabus', $Uparam, $Uwhere);
                    $this->session->set_flashdata('alert_success', lang('addsuccess'));
                else:
                    $encryptId            = $this->input->post('CurrentDataID');
                   
                    $param['update_date'] = currentDateTime();
                    $param['updated_by']  = $this->session->userdata('SMS_ADMIN_ID');
                    $this->common_model->edit_data('class_syllabus', $param, $encryptId);
                    $this->session->set_flashdata('alert_success', lang('updatesuccess'));
                endif;

            //redirect(correctLink('classListAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));
            endif;
        endif;

        $this->layouts->set_title('Edit syllabus details');
        $this->layouts->admin_view('classlist/syllabus', array(), $data);
    }

}
