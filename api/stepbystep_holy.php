<?
/*
 * ����� ��� ������� ���������� � ��������� �����������.
 * 
 * ������ �������������:
 * $steps=new Holy_Steps(Array(
  "count"=>0,
  "url"=>"/engine/admin/something.php",
  "step_add"=>10,
  "complete_message"=>"������ ��������.",
  ));
 * $steps->all_count=count($item_array);
 * if (($step_num=$steps->GetStep())===-1)
 * {
 * $steps->Draw();
 * $steps->Start();
 * };
 * if (($step_num=$steps->GetStep())>=0)
 * {
 * [...��������...]
 * ]
 */

class Holy_Steps {

    var $step_var;
    var $step;
    var $step_add;
    var $url;
    var $all_count;
    var $complete;

    /**
     * �����������
     * 
     * @param array $params  <p>���������:<br>
     * count - ����� ����� �����. ������ - 1�<br>
     * url - �� ������ ������ ���������� (� ����� ���� ������������ ����� ���� � ���������� ����)<br>
     * step_add - �� ������� ��������� ���������� � �������� (�� ��������� - 1)<br>
     * step_var - ���������� ���� (�� ��������� - step_var )<br>
     * </p>
     */
    function Holy_Steps($params) {
        if (!isset($params['step_var']))
            $params['step_var'] = 'step_var';
        if (!isset($params['step_add']))
            $params['step_add'] = 1;
        if (!isset($params['complete_message']))
            $params['complete_message'] = "������ ���������.";

        $this->step_var = $params['step_var'];
        $this->step_add = $params['step_add'];
        $this->url = $params['url'];
        $this->all_count = $params['count'];
        $this->complete_message = $params['complete_message'];
        $this->step = -1;
        $this->complete = false;
    }

    /**
     * ����� ������� ���������� ��������.
     * 
     */
    function Draw() {
        ?>
        <div class="progress progress-striped
             active percent_holy_div">
            <div class="bar" style="width: 0%;" id="percent_holy" name="percent_holy"></div>
        </div>
        <?
    }

    /**
     * ������ �������
     * 
     */
    function Start() { //������ �������
        $this->step = 0;
        ?>
        <script>
            //var max=<?= $this->all_count / 100; ?>;
            var num=0;
            var percent=0;
            var complete=false;
            function GetData(data)
            {
                if (!complete)
                {
                    if (num><?= $this->all_count ?>)
                    {num=<?= $this->all_count ?>;complete=true;};
                    if (num<=<?= $this->all_count ?>)
                    {
                        $.get(
                        "<?= $this->url ?>",
                        {
        <?= $this->step_var ?>: num,
                        },
                        GetData
                    );
                        num=num+<?= $this->step_add ?>;
                    };
                    percent=(100*num)/<?= $this->all_count ?>;
                    //alert(num+" "+percent);
                    if (percent>100.0) {percent=100.0;complete=true};
                    if (percent==100.0) complete=true;
                    $("#percent_holy").width(percent+"%");
                }else
                {
                    //alert("complete!");
                    $(".percent_holy_div").after('<div class="alert alert-success"><?= $this->complete_message ?></div>');
                };
            };
            GetData();
        </script>
        <?
    }

    /**
     * ���������� ����� �������� ����. -1 - ���� ��� ����� ������� ��� ������ ����.
     * 
     * @return int <p>����� ����</p>
     */
    function GetStep() { //���������� ����� ����, � -1, ���� ������� �� ������
        if ($this->step == -1)
            if (isset($_GET[$this->step_var]))
                $this->step = $_GET[$this->step_var];

        if ($this->step >= $this->all_count)
            $this->complete = true;
        return $this->step;
    }

}

;
?>