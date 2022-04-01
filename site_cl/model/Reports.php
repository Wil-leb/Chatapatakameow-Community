<?php

namespace App\model;

use App\core\Connect;

class Reports extends Connect {
    
    protected $_pdo;
    private $_formerReport;

    public function __construct() {
        $this->_pdo = $this->connection();
        $this->_formerReport;
    }

//*****A. Finding the existing reports of a specific element*****//
    private function recordExists(string $refId, string $category) {
        $sql = $this->_pdo->prepare("SELECT `id` FROM $category WHERE `id` = :id");
        
        $sql->execute([":id" => $refId]);

        if($sql->rowCount() == 0) {
            die("Impossible de signaler un élément qui n'existe pas !");
        }
    }

//*****B. Report addition*****//
    private function addReport(string $id, string $publisherId, string $refId, string $category, string $userIp, int $report) {
        $this->recordExists($refId, $category);

        $sql = "SELECT `id`, `report` FROM `reports` WHERE `publisher_id` = :publisherId AND `ref_id` = :refId
                AND `category` = :category AND `user_ip` = :userIp";

        $query = $this->_pdo->prepare($sql);

        $query->execute([":publisherId" => $publisherId, ":refId" => $refId, ":category" => $category, ":userIp" => $userIp]);

        $reportRow = $query->fetch();

        if($reportRow) {
            if($reportRow["report"] == $report) {
                $query = $this->_pdo->prepare("DELETE FROM `reports` WHERE `id` = :id");

                $query->execute([':id' => $reportRow['id']]);

                return false;
            }

            $this->_formerReport = $reportRow;

            $sql = "UPDATE `reports` SET `report` = :report WHERE `id` = :id";

            $query = $this->_pdo->prepare($sql);

            $query->execute([":id" => $reportRow["id"], ":report" => $report]);

            return true;
        }

        $req = "INSERT INTO `reports` (`id`, `publisher_id`, `ref_id`, `category`, `user_ip`, `report`)
                VALUES (:id, :publisherId, :refId, :category, :userIp, :report)";

        $query = $this->_pdo->prepare($req);
        
        $query->execute([
                        ":id" => $id,
                        ":publisherId" => $publisherId,
                        ":refId" => $refId,
                        ":category" => $category,
                        ":userIp" => $userIp,
                        ":report" => $report
                        ]);

        return true;
    }

//*****C. Report update*****//
    public function report(string $id, string $publisherId, string $refId, string $category, string $userIp) {
        if($this->addReport($id, $publisherId, $refId, $category, $userIp, 1)) {
            $sqlPart = "";

            if($this->_formerReport) {
                $sqlPart = ", `reports_number` = `reports_number` - 1";
            }

            $que = "UPDATE $category SET `reports_number` = `reports_number` + 1 $sqlPart WHERE `id` = :refId";

            $query = $this->_pdo->prepare($que);

            $query->execute([":refId" => $refId]);

            return true;
        }

        else {
            $query = $this->_pdo->prepare("UPDATE $category SET `reports_number` = `reports_number` - 1 WHERE `id` = :refId");
            $query->execute([":refId" => $refId]);
        }

        return false;
    }

//*****D. Modifying the report count*****//
    public function updateReportCount(string $refId, string $category) {
        $sql = "SELECT COUNT(id) AS reportCount, `report` FROM `reports` WHERE `ref_id` = :refId AND `category` = :category
                GROUP BY `report`";

        $query = $this->_pdo->prepare($sql);
        
        $query->execute([":refId" => $refId, ":category" => $category]);

        $reports = $query->fetchAll();

        $count = ["1" => 0];

        foreach($reports as $report) {
            $count[$report["report"]] = $report["reportCount"];
        }

        $req = "UPDATE $category SET `reports_number` = :reports_number WHERE `id` = :refId";

        $query = $this->_pdo->prepare($req);

        $query->execute([":refId" => $refId, ":reports_number" => $count[1]]);

        return true;
    }

//*****E. Finding all the reported content of a specific user*****//
    public function countUserReports(string $publisherId) {
        $sql = "SELECT COUNT(report) AS totalReports FROM `reports` WHERE `publisher_id` = :publisherId GROUP BY `publisher_id`";

        $query = $this->_pdo->prepare($sql);
        
        $query->execute([":publisherId" => $publisherId]);

        return $query->fetch(\PDO::FETCH_ASSOC);
    }

//*****END OF THE Votes MODEL*****//
}