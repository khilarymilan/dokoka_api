<?php

namespace App\Repositories;

use App\Exceptions\StaffException;
use App\Models\Staff;

/**
 * Class StaffRepository
 * @package App\Repositories
 *
 * @property Staff $model
 */
class StaffRepository extends Repository
{
    public function validate($model)
    {
        if (is_array($model)) {
            $model = new Staff($model);
        }

        $model->nickname = trim($model->nickname);

        empty($model->nickname) &&
        StaffException::throwEmptyName();

        if ($idOfName = $this->findByName($model->nickname, ['id'])) {
            $idOfName = $idOfName->id;

            empty($model->id) && $idOfName &&
            StaffException::throwDuplicateName();

            !empty($model->id) && $idOfName != $model->id &&
            StaffException::throwDuplicateName();
        }

        empty($model->dmm_admin_user_id) && StaffException::throwEmptyDmmAdminAccount();

        empty($model->branch_id) &&
        StaffException::throwEmptyBranch();

        empty($model->branch_team_id) &&
        StaffException::throwEmptyTeam();
    }

    public function saving($model)
    {
        $this->validate($model);
    }

    /**
     * Find category by unique nickname
     *
     * @param string $name
     * @param array $columns
     * @return null | Staff
     */
    public function findByName($name, $columns = ['*'])
    {
        return $this->model->select($columns)->whereNickname($name)->first();
    }

    public function getBranchStaffs()
    {
        $rows = self::selectSql('getBranchStaffs');

        $branch_staff_map = [];
        foreach ($rows as $row) {
            if (!isset($branch_staff_map[$row->branch_id])) {
                $branch_staff_map[$row->branch_id] = [
                    'details' => [
                        'branch_name' => $row->branch_name,
                    ],
                ];
            }
            $branch_staff_map[$row->branch_id]['staffs'][$row->staff_id] = [
                'nickname' => $row->nickname,
                'is_active' => $row->is_active,
            ];
        }

        return $branch_staff_map;
    }

    public function isAssignableToInquiryByAdminId($inquiry, $assignor_dmm_admin_id)
    {
        $assignee_staff = $this;
        $inquiry = new InquiryRepository($inquiry);

        $qry =
            self::sql(
                'isAssigneeStaffAssignableToInquiryByAssignorAdminId',
                [
                    'ASSIGNEE_STAFF_ID' => $assignee_staff->id,
                    'ASSIGNEE_STAFF_BRANCH_ID' => $assignee_staff->branch_id,
                    'ASSIGNOR_DMM_ADMIN_ID' => $assignor_dmm_admin_id,
                    'INQUIRY_INBOX_ID' => $inquiry->inbox_id,
                    'ARR_POSSIBLE_ASSIGNOR_BRANCHES' => [
                        'assignor_staff_branch.id',
                        'branch_assignees.assignee_branch_id'
                    ],
                ]
            );

        return count(\DB::select($qry)) > 0;
    }
}
