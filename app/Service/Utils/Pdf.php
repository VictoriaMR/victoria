<?php

/**
 * url处理相关封装
 *
 * @date: 2018/05/04
 */

namespace App\Service\Utils;

class Pdf
{
    public static function download($params, $dest = 'D')
    {

        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        // set header and footer fonts
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        
        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->setPrintHeader(true);
        $pdf->setPrintFooter(true);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10); 
        // $pdf->SetMargins(15, 27, 15);
        
        // set font
        $pdf->SetFont('stsongstdlight', '', 20);

        // add a page
        $pdf->AddPage();

        // if($params['title']) {
        //     $pdf->Write(0, $params['title'], '', 0, 'C', true, 0, false, false, 0);
        //     $pdf->Write(3, "", '', 0, 'L', true, 0, false, false, 0);
        // }   
        // $pdf->MultiCell(0, 0, '留学咨询服务合同', $border=0, $align='C', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=FALSE, $autopadding=true, $maxh=0);
        $pdf->Write(0, '留学咨询服务合同', '', 0, 'C', true, 0, false, false, 0);
        // $pdf->Write(3, "", '', 0, 'L', true, 0, false, false, 0);
        // $pdf->Write(3, "", '', 0, 'L', true, 0, false, false, 0);
        $pdf->SetFont('stsongstdlight', '', 9);
        $pdf->Write(0, "合同编号：" . $params['contract_no'], '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "合同金额：" . $params['price'], '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "实付金额：" . $params['subtotal'], '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "优惠金额：" . $params['discount_amount'], '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "金额无条件退款期：自合同生效后" . $params['trial_term'] . "天内", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "合同生效时间：" . $params['paid_at'], '', 0, 'L', true, 0, false, false, 0);

        $pdf->Ln();
        $pdf->Write(0, "委托人：" . $params['student_name'], '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "身份证/护照号码：" . $params['student_card_no'], '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "移动电话：" . $params['student_mobile'], '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "紧急联系人：{$params['emergency_contact']},{$params['emergency_mobile']},", '', 0, 'L', true, 0, false, false, 0);

        $pdf->Ln();
        if ($params['studio_type'] > 0 && $params['studio_id'] > 0) {
            $pdf->Write(0, "受托人：" . $params['studio_company'], '', 0, 'L', true, 0, false, false, 0);
            $pdf->Write(0, "社会统一信用代码：" . $params['business_licence_no'], '', 0, 'L', true, 0, false, false, 0);
            $pdf->Write(0, "项目负责人：" . $params['consultant_name'], '', 0, 'L', true, 0, false, false, 0);
            $pdf->Ln();
            $pdf->Write(0, "身份证/护照号码：" . $params['consultant_card_no'], '', 0, 'L', true, 0, false, false, 0);
            $pdf->Write(0, "移动电话：" . $params['consultant_mobile'], '', 0, 'L', true, 0, false, false, 0);
        } elseif ($params['consultant_business_at']) {
            $pdf->Write(0, "受托人：" . $params['consultant_company_name'], '', 0, 'L', true, 0, false, false, 0);
            $pdf->Write(0, "社会统一信用代码：" . $params['consultant_company_business_licence_no'], '', 0, 'L', true, 0, false, false, 0);
            $pdf->Write(0, "项目负责人：" . $params['consultant_name'], '', 0, 'L', true, 0, false, false, 0);
            $pdf->Ln();
            $pdf->Write(0, "身份证/护照号码：" . $params['consultant_card_no'], '', 0, 'L', true, 0, false, false, 0);
            $pdf->Write(0, "移动电话：" . $params['consultant_mobile'], '', 0, 'L', true, 0, false, false, 0);
        } else {
            $pdf->Write(0, "受托人：" . $params['consultant_name'], '', 0, 'L', true, 0, false, false, 0);
            $pdf->Write(0, "身份证/护照号码：" . $params['consultant_card_no'], '', 0, 'L', true, 0, false, false, 0);
            $pdf->Write(0, "移动电话：" . $params['consultant_mobile'], '', 0, 'L', true, 0, false, false, 0);
        }

        $pdf->Write(3, "", '', 0, 'L', true, 0, false, false, 0);
        $pdf->writeHTML(self::code_to_string($params['content']));
        $pdf->Write(10, "", '', 0, 'L', true, 0, false, false, 0);
        // set font
        $pdf->SetFont('stsongstdlight', '', 9);

        $pdf->Ln(0);
        // if ($params['created_at'] < '2018-09-18') {
        //     $pdf->Write(0, "受托人（签字）：", '', 0, 'L', false, 0, false, false, 0);
        //     $pdf->Write(0, "阿基德米（北京）科技有限公司", '', 0, 'R', true, 0, false, false, 0);
        //     $pdf->Write(0, "身份证号码：", '', 0, 'L', false, 0, false, false, 0);
        //     $pdf->Write(0, "（盖章）", '', 0, 'R', true, 0, false, false, 0);

        //     $pdf->Ln();
        //     $pdf->Write(0, "委托人（签字）：", '', 0, 'L', true, 0, false, false, 0);
        //     $pdf->Write(0, "身份证号码：", '', 0, 'L', true, 0, false, false, 0);

        //     $pdf->Ln();
        //     $pdf->Write(0, "签订日期：", '', 0, 'L', true, 0, false, false, 0);
        // } else {
        //     $pdf->Write(0, "受托人（签字）：", '', 0, 'L', true, 0, false, false, 0);
        //     $pdf->Write(0, "身份证号码：", '', 0, 'L', true, 0, false, false, 0);

        //     $pdf->Ln();
        //     $pdf->Write(0, "委托人（签字）：", '', 0, 'L', true, 0, false, false, 0);
        //     $pdf->Write(0, "身份证号码：", '', 0, 'L', true, 0, false, false, 0);

        //     $pdf->Ln();
        //     $pdf->Write(0, "签订日期：", '', 0, 'L', true, 0, false, false, 0);
        // }
        $pdf->Write(0, "委托人（签字）：", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "身份证号码：", '', 0, 'L', true, 0, false, false, 0);

        $pdf->Ln();
        $pdf->Write(0, "受托人（签字）：", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "身份证号码：", '', 0, 'L', true, 0, false, false, 0);

        $pdf->Ln();
        $pdf->Write(0, "签订日期：", '', 0, 'L', true, 0, false, false, 0);

        
        
        //Close and output PDF document
        $files = $dest == 'F' ?  storage_path("app/temp/{$params['contract_no']}.pdf")  : "{$params['contract_no']}.pdf";
        $pdf->Output($files, $dest);

        return "temp/{$params['contract_no']}.pdf";
    }

    public static function downloadTripartit($params, $dest = 'D')
    {
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        // set header and footer fonts
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        
        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->setPrintHeader(true);
        $pdf->setPrintFooter(true);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);
        $pdf->SetMargins(15, 27, 15);
        
        // set font
        $pdf->SetFont('stsongstdlight', '', 20);

        // add a page
        $pdf->AddPage();

        // if($params['title']) {
        //     $pdf->Write(0, $params['title'], '', 0, 'C', true, 0, false, false, 0);
        //     $pdf->Write(3, "", '', 0, 'L', true, 0, false, false, 0);
        // }   
        // $pdf->MultiCell(0, 0, '留学咨询服务合同', $border=0, $align='C', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=FALSE, $autopadding=true, $maxh=0);
        // 

        $pdf->Write(0, '服务费监管协议', '', 0, 'C', true, 0, false, false, 0);
        $pdf->Write(3, "", '', 0, 'L', true, 0, false, false, 0);
        // $pdf->Write(3, "", '', 0, 'L', true, 0, false, false, 0);
        // 
        // 
        // 
        // 
        $pdf->SetFont('stsongstdlight', 'B', 12);
        $content = "\t\t尊敬的客户：为了保障您的合法权益，请您务必在确认本协议前仔细阅读本协议的全部内容（尤其是加粗部分）。如果有疑问，可联系平台工作人员，或致电400-000-9664。\n\t\t您在线点击确认本协议的行为即视为您同意订立本协议，在留学咨询服务合同委托人点击确认后，本协议立即生效；您上述的行为将被理解为您同意按照本协议的约定，接受留学快问平台提供的监管保障，并履行相应的权利与义务。";
        $pdf->Write(7, $content, '', 0, '', true, 0, false, false, 0);
        $pdf->Write(3, "", '', 0, 'L', true, 0, false, false, 0);

        $content = "\t\t留学咨询服务合同受托人{$params['sign_name']}（以下称为“受托人”）已入驻留学快问平台，并与留学咨询服务合同委托人{$params['student_name']}（以下称为“委托人”）签署《留学服务合同》（具体名称以在留学快问平台系统上签约的合同或者备案至留学快问平台的版本为准，下同），该合同在留学快问平台系统中编号为{$params['contract_no']}。";
        $pdf->Write(7, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->SetFont('stsongstdlight', '', 12);
        $content = "\t\t为进一步规范受托人在留学快问平台的服务，保障委托人的合法权益，委托人与受托人同意引入留学快问平台对双方签署的《留学咨询服务合同》的服务费用进行监管，并达成如下服务费用监管协议，以资共同遵守。";
        $pdf->Write(7, $content, '', 0, '', true, 0, false, false, 0);

        $content = "\t\t服务费用监管协议主要包含两种监管方式：";
        $pdf->Write(7, $content, '', 0, '', 0, 0, false, false, 0);
        $pdf->SetFont('stsongstdlight', 'B', 12);
        $content = "资金托管及保证金监管。";
        $pdf->Write(7, $content, '', 0, '', 0, 0, false, false, 0);
        $pdf->SetFont('stsongstdlight', '', 12);
        $content = "受托人可以选择为委托";
        $pdf->Write(7, $content, '', 0, '', true, 0, false, false, 0);
        $content = "人提供其中一项或者全部的监管保障。";
        $pdf->Write(7, $content, '', 0, '', true, 0, false, false, 0);
        $pdf->Write(3, "", '', 0, 'L', true, 0, false, false, 0);

        $pdf->SetFont('stsongstdlight', 'B', 14);
        $pdf->Write(14, '一、 资金托管', '', 0, 'L', true, 0, false, false, 0);
        $pdf->SetFont('stsongstdlight', 'B', 12);

        $pdf->SetMargins(23, 27, 15);
        $content = "1.\t 委托人及受托人知晓并确认，在《留学咨询服务合同》生效期间，委托人{$params['enjoy_type']}由留学快问平台提供的资金托管服务。如不享受资金托管服务，则本协议第一条第2款至第一条第5款不发生效力。";
        $pdf->Write(7, $content, '', 0, 'L', true, 0, false, false, 0, 0);
        $pdf->SetMargins(15, 27, 15);
        $pdf->Ln(0);
        $pdf->SetMargins(23, 27, 15);
        $pdf->SetFont('stsongstdlight', '', 12);
        $content = "2.\t 托管金作为受托人的履约保证金，用于担保受托人与委托人之间合同义务的履行。如委托人与受托人接受此项监管保障服务，合同履行期间，委托人享有留学快问平台为委托人提供的资金托管服务。";
        $pdf->Write(7, $content, '', 0, 'L', 0, 0, false, false, 0, 0);
        $pdf->SetFont('stsongstdlight', 'B', 12);
        $content = "托管金作为受托人的履约保证金，用于担保受托人与委托人之间合同义务的";
        $pdf->Write(7, $content, '', 0, 'L', true, 0, false, false, 0, 0);
        $content = "履行。如委托人与受托人接受此项监管保障服务，合同履行期间，委托人享有留学快问平台为委托人提供的资金托管服务。";
        $pdf->Write(7, $content, '', 0, 'L', true, 0, false, false, 0, 0);
        $pdf->SetMargins(15, 27, 15);
        $pdf->Ln(0);
        $pdf->SetMargins(23, 27, 15);
        $content = "3.\t 托管金结算与退还：";
        $pdf->Write(7, $content, '', 0, 'L', true, 0, false, false, 0, 0);
        $pdf->SetFont('stsongstdlight', '', 12);
        $content = "符合任一以下情形时，留学快问平台将对托管金进行相应的处置：";
        $pdf->Write(7, $content, '', 0, 'L', true, 0, false, false, 0, 0);
        $content = " 1)  当委托人及受托人在留学快问平台系统中确认合同完成后，留学快问平台将会与受托人结算托管金；";
        $pdf->Write(7, $content, '', 0, 'L', true, 0, false, false, 0, 0);
        $content = " 2)  若委托人及受托人的《留学咨询服务合同》期限届满或合同约定的退款申请截止日期届满（如合同中没有约定退款申请截止日期，则以合同项下入学年份的9月1日为截止日期），委托人既未在留学快问平台系统内提交退款申请，也未确认合同完成的，则视为委托人默认该合同完成，留学快问平台将会与受托人结算托管金;";
        $pdf->Write(7, $content, '', 0, 'L', true, 0, false, false, 0, 0);
        $content = " 3)  当委托人及受托人在留学快问平台系统中确认退款申请后，留学快问平台将按照双方确认的退款金额结算托管金；";
        $pdf->Write(7, $content, '', 0, 'L', true, 0, false, false, 0, 0);
        $content = " 4)  依据生效的法律文书要求。";
        $pdf->Write(7, $content, '', 0, 'L', true, 0, false, false, 0, 0);
        $pdf->SetMargins(15, 27, 15);
        $pdf->Ln(0);
        $pdf->SetMargins(23, 27, 15);
        $pdf->SetFont('stsongstdlight', 'B', 12);
        $content = "4.\t 退还责任豁免：";
        $pdf->Write(7, $content, '', 0, 'L', 0, 0, false, false, 0, 0);
        $pdf->SetFont('stsongstdlight', '', 12);
        $content = "当留学快问平台按照上述约定完成托管金退还后，留学快问平台将自动退出";
        $pdf->Write(7, $content, '', 0, 'L', true, 0, false, false, 0, 0);
        $content = "委托人与受托人之间的争议；届时无论委托人与受托人之间是否存在后续争议，均不得向留学快问平台主张任何权利或追究留学快问平台的任何责任。但为促使委托人与受托人之间的争议妥善解决，留学快问平台同意提供必要的配合与协助工作，如提供必要的文件资料等。";
        $pdf->Write(7, $content, '', 0, 'L', true, 0, false, false, 0, 0);
        $pdf->SetMargins(15, 27, 15);
        $pdf->Ln(0);
        $pdf->SetMargins(23, 27, 15);
        $pdf->SetFont('stsongstdlight', 'B', 12);
        $content = "5.\t 无息支付/退还：";
        $pdf->Write(7, $content, '', 0, 'L', 0, 0, false, false, 0, 0);
        $pdf->SetFont('stsongstdlight', '', 12);
        $content = "无论因上述何种原因发生托管金结算或退还，留学快问平台均为无息支付";
        $pdf->Write(7, $content, '', 0, 'L', true, 0, false, false, 0, 0);
        $content = "，委托人同意不因托管期限及其中止、中断、延长而向留学快问平台追索任何利息或资金占用费。";
        $pdf->Write(7, $content, '', 0, 'L', true, 0, false, false, 0, 0);

        $pdf->SetMargins(15, 27, 15);
        $pdf->Ln(0);
        $pdf->SetFont('stsongstdlight', 'B', 14);
        $pdf->Write(14, '二、 保证金监管', '', 0, 'L', true, 0, false, false, 0);
        $pdf->SetMargins(23, 27, 15);
        $pdf->SetFont('stsongstdlight', '', 12);
        $pdf->Ln(0);
        $content = "保证金是受托人增加自身履约资信能力的体现。当受托人出现违反《留学咨询服务合同》的情况时，委托人有权按照本协议或留学快问平台规则的规定，向留学快问申请冻结保证金。";
        $pdf->Write(7, $content, '', 0, 'L', true, 0, false, false, 0, 0);
        $pdf->SetFont('stsongstdlight', 'B', 12);
        $pdf->SetMargins(15, 27, 15);
        $pdf->SetFont('stsongstdlight', '', 12);
        $pdf->Ln(0);
        $pdf->Write(7, "1.\t 保证金冻结:", '', 0, 'L', true, 0, false, false, 0);
        $pdf->SetMargins(23, 27, 15);
        $pdf->SetFont('stsongstdlight', '', 12);
        $pdf->Ln(0);
        $pdf->Write(7, " 1)   同时满足以下情形的，委托人有权向留学快问提出冻结保证金：", '', 0, 'L', true, 0, false, false, 0);
        $pdf->SetMargins(30, 27, 15);
        $pdf->Ln(0);
        $pdf->Write(7, " a)   受托人在留学快问平台上有预存保证金；", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(7, " b)   委托人在《留学咨询服务合同》生效期间，与受托人产生合同纠纷且不可调和；", '', 0, 'L', true, 0, false, false, 0);
        $pdf->SetMargins(23, 27, 15);
        $pdf->Ln(0);
        $pdf->Write(7, " 2)   委托人提出的冻结申请，须经留学快问平台审核通过后再执行。", '', 0, 'L', true, 0, false, false, 0);
        $pdf->SetMargins(15, 27, 15);
        $pdf->Ln(0);
        $pdf->SetFont('stsongstdlight', 'B', 12);
        $pdf->Write(7, "2.\t 保证金解冻：满足以下任一情形，已冻结的保证金将被解冻：", '', 0, 'L', true, 0, false, false, 0);
        $pdf->SetMargins(23, 27, 15);
        $pdf->SetFont('stsongstdlight', '', 12);
        $pdf->Ln(0);
        $pdf->Write(7, " 1)委托人在《留学咨询服务合同》生效期间，申请冻结保证金并已被留学快问平台核实后成功冻结，委托人向平台提出解除冻结，并且被留学快问平台核实的；", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(7, " 2)   委托人与留学服务受托人在留学快问系统中确认合同完成；", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(7, " 3)   委托人与留学服务受托人在留学快问系统中确认合同退款；", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(7, " 4)   若委托人及受托人的《留学咨询服务合同》期限届满或合同约定的退款申请截止日期届满（如合同中没有约定退款申请截止日期，则以合同项下入学年份的9月1日为截止日期），委托人既未在留学快问平台系统内提交退款申请，也未确认合同完成的；", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(7, " 5)   依据生效的法律文书要求。", '', 0, 'L', true, 0, false, false, 0);
        $pdf->SetMargins(15, 27, 15);
        $pdf->Ln(0);
        $pdf->SetMargins(23, 27, 15);
        $pdf->SetFont('stsongstdlight', 'B', 12);
        $pdf->Write(7, "3.\t 冻结额度：保证金的冻结额度将不能超过委托人的留学咨询服务合同的合同金额，亦不能超过申请冻结时受托人现存保证金数额。", '', 0, 'L', true, 0, false, false, 0);
        $pdf->SetMargins(15, 27, 15);
        $pdf->Ln(0);
        $pdf->SetMargins(23, 27, 15);
        $pdf->Write(7, "4.\t 解冻责任豁免：", '', 0, 'L', 0, 0, false, false, 0);
        $pdf->SetFont('stsongstdlight', '', 12);
        $pdf->Write(7, "当留学快问平台按照上述约定完成保证金的解冻后，当留学快问平台按照上", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(7, "述约定完成保证金的解冻后，留学快问平台将自动退出委托人与受托人之间的争议；届时，除非委托人再次申请冻结保证金并被留学快问平台核实后冻结保证金，否则无论委托人与受托人之间是否存在后续争议，均不得向留学快问平台主张任何权利或追究留学快问的任何责任。但为促使委托人与受托人之间的争议妥善解决，留学快问平台同意提供必要的配合与协助工作，如提供必要的文件资料等。", '', 0, 'L', true, 0, false, false, 0);

        $pdf->SetMargins(15, 27, 15);
        $pdf->Ln(0);
        $pdf->SetFont('stsongstdlight', 'B', 14);
        $pdf->Write(14, '三、 争议豁免', '', 0, 'L', true, 0, false, false, 0);
        $pdf->SetMargins(23, 27, 15);
        $pdf->SetFont('stsongstdlight', 'B', 12);
        $pdf->Ln(0);
        $content = "委托人在此确认并同意，留学快问系独立第三方，在委托人与受托人发生争议时，留学快问将竭尽所能调和矛盾。但留学快问并非享有裁决权力的司法或行政部门，也无法仅凭一方之言判断争议的真实情况。当委托人与受托人双方之间的矛盾无法调和，应当诉诸诉讼或仲裁程序。留学快问将根据留学服务合同委托人或受托人提供的生效裁判文书内容，执行退款或采取相应技术措施。";
        $pdf->Write(7, $content, '', 0, 'L', true, 0, false, false, 0, 0);

        $pdf->SetMargins(15, 27, 15);
        $pdf->Ln(0);
        $pdf->SetFont('stsongstdlight', 'B', 14);
        $pdf->Write(14, '四、 协议变更和终止', '', 0, 'L', true, 0, false, false, 0);
        $pdf->SetMargins(23, 27, 15);
        $pdf->SetFont('stsongstdlight', '', 12);
        $content = "1.\t 本协议未尽事宜，以留学快问平台规则为准。留学快问平台有权根据国家法律法规变化及维护交易秩序、保护消费者权益的需要，不时修改本协议及相应平台规则，并以《留学快问平台用户注册协议》约定的方式通知及生效。";
        $pdf->Write(7, $content, '', 0, 'L', true, 0, false, false, 0, 0);
        $pdf->SetMargins(15, 27, 15);
        $pdf->Ln(0);
        $pdf->SetMargins(23, 27, 15);
        $pdf->SetFont('stsongstdlight', '', 12);
        $content = "2.\t 非依本协议约定方式、留学快问平台规则规定方式或法律法规规定方式的终止，本协议将长期保持有效。";
        $pdf->Write(7, $content, '', 0, 'L', true, 0, false, false, 0, 0);

        $pdf->SetMargins(15, 27, 15);
        $pdf->Ln(0);
        $pdf->SetFont('stsongstdlight', 'B', 14);
        $pdf->Write(14, '五、 其他', '', 0, 'L', true, 0, false, false, 0);
        $pdf->SetMargins(23, 27, 15);
        $pdf->SetFont('stsongstdlight', '', 12);
        $content = "1.\t 本协议适用中华人民共和国大陆地区法律。";
        $pdf->Write(7, $content, '', 0, 'L', true, 0, false, false, 0, 0);
        $pdf->SetMargins(15, 27, 15);
        $pdf->Ln(0);
        $pdf->SetMargins(23, 27, 15);
        $pdf->SetFont('stsongstdlight', '', 12);
        $content = "2.\t 本协议任一条款被视为废止、无效或不可执行，该条应视为可分的且并不影响本协议其余条款的有效性及可执行性。";
        $pdf->Write(7, $content, '', 0, 'L', true, 0, false, false, 0, 0);
        $pdf->SetMargins(15, 27, 15);
        $pdf->Ln(0);
        $pdf->SetMargins(23, 27, 15);
        $pdf->SetFont('stsongstdlight', '', 12);
        $content = "3.\t 本协议为《留学快问平台用户注册协议》的补充协议，本协议与《留学快问平台用户注册协议》不一致之处，以本协议为准。";
        $pdf->Write(7, $content, '', 0, 'L', true, 0, false, false, 0, 0);



        // $pdf->Write(3, "", '', 0, 'L', true, 0, false, false, 0);
        // $pdf->Write(3, "--------------------------------------------", '', 0, 'L', true, 0, false, false, 0);
        // $pdf->SetFont('stsongstdlight', '', 12);
        // $pdf->Write(0, '服务费监管协议', '', 0, '', 0, 0, false, false, 0);
        // $pdf->SetFont('stsongstdlight', 'B', 12);
        // $pdf->Write(0, '服务费监管协议', '', 0, '', 0, 0, false, false, 0);
        // $pdf->Write(3, "", '', 0, 'L', true, 0, false, false, 0);
        // //内容 与下一个单元格的相对位置 是否填充颜色 是否重置高度 未知 文本对齐方式
        // $pdf->writeHTML(self::code_to_string_tripartit($params['tripartite_contract']),0,0,1,0);
        // $pdf->Write(10, "", '', 0, 'L', true, 0, false, false, 0);

        // // $pdf->writeHTML($params['tripartite_contract']);


        //Close and output PDF document
        // return $pdf->Output("{$params['contract_no']}-tripartite.pdf", $dest);

        //Close and output PDF document
        $files = $dest == 'F' ?  storage_path("app/temp/{$params['contract_no']}-tripartite.pdf")  : "{$params['contract_no']}-tripartite.pdf";
        $pdf->Output($files, $dest);

        return "temp/{$params['contract_no']}-tripartite.pdf";
    }

    public static function downloadEdit($params, $dest = 'D')
    {

        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        // set header and footer fonts
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        
        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->setPrintHeader(true);
        $pdf->setPrintFooter(true);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10); 
        // $pdf->SetMargins(15, 27, 15);
        
        // set font
        $pdf->SetFont('stsongstdlight', '', 20);

        // add a page
        $pdf->AddPage();

        // if($params['title']) {
        //     $pdf->Write(0, $params['title'], '', 0, 'C', true, 0, false, false, 0);
        //     $pdf->Write(3, "", '', 0, 'L', true, 0, false, false, 0);
        // }   
        // $pdf->MultiCell(0, 0, '留学咨询服务合同', $border=0, $align='C', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=FALSE, $autopadding=true, $maxh=0);
        $pdf->Write(0, $params['title'], '', 0, 'C', true, 0, false, false, 0);
        $pdf->SetFont('stsongstdlight', '', 9);
        $pdf->Write(3, "", '', 0, 'L', true, 0, false, false, 0);
        $pdf->writeHTML(self::code_to_string($params['additional_content']));
        $pdf->Write(10, "", '', 0, 'L', true, 0, false, false, 0);
        // set font
        //         
        //Close and output PDF document
        //$pdf->Output("{$params['key']}.pdf", $dest);
        //
        $files = $dest == 'F' ?  storage_path("app/temp/{$params['key']}.pdf")  : "{$params['key']}.pdf";
        $pdf->Output($files, $dest);

        return "temp/{$params['key']}.pdf";
    }

    /**
     * 换行符等转换成html格式输出
     **/
    public static function code_to_string($str)
    {
        /*
         * \t:水平制表（跳到下一个Tab位置）意思是按一个tab
         * \n:换行
         * \r:回车，将当前位置移到本行开头
         */

        $pre = array("\t", "\n\r", "<span style='width: 20px; display: inline-block'></span>");
        $to = array('&nbsp;&nbsp;&nbsp;&nbsp;', '<br>', '&nbsp;&nbsp;&nbsp;&nbsp;');
        return str_replace($pre, $to, $str);
    }

    /**
     * 换行符等转换成html格式输出
     **/
    public static function code_to_string_tripartit($str)
    {
        /*
         * \t:水平制表（跳到下一个Tab位置）意思是按一个tab
         * \n:换行
         * \r:回车，将当前位置移到本行开头
         */

        $pre = array("<div style='font-size:20px;font-weight:bold;text-align:center;'>服务费用监管协议</div>",

        );
        $to = array('', );
        return str_replace($pre, $to, $str);
    }

    public static function downloadFreeAgent($params, $dest = 'D')
    {
        $fontSize = 11;
        $leftpadding = 6;
        $lineHeight = 6;
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        // set header and footer fonts
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        
        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->setPrintHeader(true);
        $pdf->setPrintFooter(true);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);
        $pdf->SetMargins(25, 27, 25);
        
        // set font
        $pdf->SetFont('stsongstdlight', '', 20);

        // add a page
        $pdf->AddPage();

        // if($params['title']) {
        //     $pdf->Write(0, $params['title'], '', 0, 'C', true, 0, false, false, 0);
        //     $pdf->Write(3, "", '', 0, 'L', true, 0, false, false, 0);
        // }   
        // $pdf->MultiCell(0, 0, '留学咨询服务合同', $border=0, $align='C', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=FALSE, $autopadding=true, $maxh=0);
        // 

        $pdf->Write(0, '免中介费申请服务合同', '', 0, 'C', true, 0, false, false, 0);
        $pdf->Write(3, "", '', 0, 'L', true, 0, false, false, 0);
        // $pdf->Write(3, "", '', 0, 'L', true, 0, false, false, 0);
        // 
        $pdf->SetFont('stsongstdlight', '', $fontSize);
        $pdf->Write(0, "编号：" . $params['contract_no'], '', 0, 'L', true, 0, false, false, 0);
        $pdf->Ln();
        $pdf->Write(0, "甲方（委托人）：" . $params['student_name'], '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "身份证/护照号码：" . $params['student_card_no'], '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "联系电话：" . $params['student_mobile'], '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "电子邮箱：" . $params['student_email'], '', 0, 'L', true, 0, false, false, 0);
        // $pdf->Write(0, "紧急联系人：{$params['emergency_contact']},{$params['emergency_mobile']},", '', 0, 'L', true, 0, false, false, 0);

        $pdf->Ln();
        if ($params['studio_type'] > 0 && $params['studio_id'] > 0) {
            $pdf->Write(0, "乙方 （受托人）：" . $params['studio_company'], '', 0, 'L', true, 0, false, false, 0);
            $pdf->Write(0, "社会统一信用代码：" . $params['business_licence_no'], '', 0, 'L', true, 0, false, false, 0);
            $pdf->Write(0, "项目负责人：" . $params['consultant_name'], '', 0, 'L', true, 0, false, false, 0);
        } elseif ($params['consultant_business_at']) {
            $pdf->Write(0, "乙方 （受托人）：" . $params['consultant_company_name'], '', 0, 'L', true, 0, false, false, 0);
            $pdf->Write(0, "社会统一信用代码：" . $params['consultant_company_business_licence_no'], '', 0, 'L', true, 0, false, false, 0);
            $pdf->Write(0, "项目负责人：" . $params['consultant_name'], '', 0, 'L', true, 0, false, false, 0);
        } else {
            $pdf->Write(0, "乙方 （受托人）：" . $params['consultant_name'], '', 0, 'L', true, 0, false, false, 0);
        }
        $pdf->Write(0, "身份证/护照号码：" . $params['consultant_card_no'], '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "联系电话：" . $params['consultant_mobile'], '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "电子邮箱：" . $params['consultant_email'], '', 0, 'L', true, 0, false, false, 0);

        $pdf->Ln();
        $pdf->Write(0, "丙方（留学快问）：" . $params['platform_company_name'], '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "统一社会信用代码：" . $params['platform_business_no'], '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "联系电话：" . $params['platform_mobile'], '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "电子邮箱：" . $params['platform_email'], '', 0, 'L', true, 0, false, false, 0);

        $pdf->Ln();

        $pdf->SetFont('stsongstdlight', '', $fontSize);
        $content = "\t\t根据《中华人民共和国合同法》及相关法律法规的规定，三方经友好协商一致，达成如下合同，以兹共同遵守：";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $pdf->Write(3, "", '', 0, 'L', true, 0, false, false, 0);

        $pdf->SetFont('stsongstdlight', 'B', $fontSize);
        $content = "一、  服务内容";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', '', $fontSize);
        $content = "1、  受托人通过留学快问平台，为委托人提供申请  ";
        $pdf->Write($lineHeight, $content, '', 0, '', false, 0, false, false, 0);
        $pdf->SetFont('stsongstdlight', 'B', $fontSize);
        $pdf->Write($lineHeight, $params['apply_year'] , '', 0, '', false, 0, false, false, 0);
        $pdf->SetFont('stsongstdlight', '', $fontSize);
        $content = "  申请季年份入学，赴  ";
        $pdf->Write($lineHeight, $content, '', 0, '', false, 0, false, false, 0);
        $pdf->SetFont('stsongstdlight', 'B', $fontSize);
        $pdf->Write($lineHeight, $params['apply_country'] , '', 0, '', false, 0, false, false, 0);
        $pdf->SetFont('stsongstdlight', '', $fontSize);
        $content = "  （国家或地区） 留学，就读  ";
        $pdf->Write($lineHeight, $content, '', 0, '', false, 0, false, false, 0);
        $pdf->SetFont('stsongstdlight', 'B', $fontSize);
        $pdf->Write($lineHeight, $params['apply_degree'] , '', 0, '', false, 0, false, false, 0);
        $pdf->SetFont('stsongstdlight', '', $fontSize);
        $content = "  学位，  ";
        $pdf->Write($lineHeight, $content, '', 0, '', false, 0, false, false, 0);
        $pdf->SetFont('stsongstdlight', 'B', $fontSize);
        $pdf->Write($lineHeight, $params['apply_major'] , '', 0, '', false, 0, false, false, 0);
        $pdf->SetFont('stsongstdlight', '', $fontSize);
        $content = "  （专业方向）或相关专业方向的留学申请咨询服务（可申请院校范围请参考留学快问-院校库，选校名单以最终双方在留学快问平台上确认申请的学校为准。委托人至少申请一所学校，英国不超过5所学校，澳洲、爱尔兰不超过3所学校。如委托人与受托人经协商一致同意申请超过以上数量，则可以超出前述限制，由此产生的额外费用由委托人与受托人另行协商确定）。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "2、  委托人和受托人应使用丙方提供的申请渠道递交学校申请。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "3、  受托人为委托人提供的留学申请咨询服务包含以下环节：";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings($leftpadding,0,0,0);
        $content = "（1）    受托人在全面深入了解委托人的留学申请需求（如专业偏好、排名偏好、地理位置偏好、职业发展倾向等）的前提下，帮助委托人对留学申请目标（如国家、专业、学位等）进行优化。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "（2）    受托人在整体分析委托人申请处境的前提下为委托人制定《服务时间规划》，并在服务过程中随时依据委托人各种主客观因素变化进行实时优化。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "（3）    在委托人既定的留学申请目标下，受托人为委托人设计选校方案，并在服务过程中随时依据委托人各种主客观因素变化进行实时优化。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "（4）    受托人针对委托人既定的目标申请学校，指导委托人制作各类必需申请材料（如：个人陈述、申请短文（essay）、个人简历、推荐信等)。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "（5）    受托人针对委托人既定的目标申请学校，为委托人提供申请手续全程指导及协助，帮助委托人降低手续出错风险。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "（6）    受托人指导委托人办理留学签证申请，降低拒签风险。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', 'B', $fontSize);
        $content = "二、  委托人权利";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', '', $fontSize);
        $content = "1、 委托人有权利了解受托人的各项服务进度，并有权利要求受托人按照留学快问平台中的《服务时间规划》在确保服务质量的前提下，按时完成服务。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "2、 委托人有权利要求受托人提供留学申请服务过程中涉及的文件、方案或者相关申请材料。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "3、 委托人对申请学校、申请材料、申请策略有最终决定权；委托人对文件寄送、录取结果选择等所有申请步骤拥有最终执行权。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', 'B', $fontSize);
        $content = "三、  委托人义务";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', '', $fontSize);
        $content = "1、  委托人须配合受托人开展申请服务，并确保在服务的各个阶段与受托人保持通讯畅通。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $pdf->SetFont('stsongstdlight', 'B', $fontSize);
        $content = "2、  委托人须向受托人提供完整真实的必需个人信息，并提供有效的证明文件及申请所需文件。如委托人隐瞒、伪造或提供不完整的资料或信息，由此产生的一切后果由委托人自行承担。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "3、  委托人同意授权受托人处理其留学申请相关事宜，委托人不可自己或授权第三方向同一所学校处理申请事宜，委托人如希望申请选校名单以外的学校，应及时主动告知受托人，得到受托人的同意，也可以授权受托人进行此工作。若委托人蓄意隐瞒申请学校或者录取结果，受托人有权利提前终止本服务合同，所收取押金不予退还，相关法律责任由委托人承担。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "4、  委托人有义务遵守留学快问平台用户协议及相关平台规则，并在服务过程中通过留学快问平台确认服务完成进度。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $content = "四、  受托人权利";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', '', $fontSize);
        $content = "1、 受托人有权利在对外发布的录取数据中包含经过去标识化、脱敏、脱密处理的委托人申请信息。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "2、 受托人有权利要求委托人及时准确地提供目标申请学校名单及其最终申请结果以确认受托人各项服务进度是否顺利开展。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "3、 受托人有权利对委托人的备考情况、个人专业水平、英文水平进行测试，以了解和保证相关咨询服务进度和计划的准确性。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', 'B', $fontSize);
        $content = "五、  受托人义务";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', '', $fontSize);
        $content = "1、 受托人应当按照中华人民共和国法律、法规和本合同约定，为委托人提供自费出国留学申请咨询服务。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "2、 受托人应当以高度勤勉、审慎的义务对待委托人的委托服务。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "3、 受托人应当以其累积的经验及掌握的信息、材料作出判断，尽最大努力维护委托人利益。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "4、 受托人在收到委托人的咨询时，应当及时回复委托人，及时完成委托事项，并应委托人要求通报工作进程。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "5、 受托人须向委托人如实介绍目标申请国家或地区的院校基本情况、专业设置情况、入学要求等相关留学申请信息，并承诺不做虚假陈述。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "6、 受托人对委托人提供的所有材料，包括个人隐私，均负有保密义务，未经委托人同意，不得向无关的第三方透露。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "7、 受托人应遵照《服务时间规划》提供服务，不得擅自更改、减少服务内容或者拖延服务周期。当需要变更或补充《服务时间规划》时，受托人应征得委托人同意。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "8、 受托人应及时向委托人提供留学申请必需的第三方服务指导（如，公证、银行、ETS等，涉及的第三方费用由委托人自行承担），以保证委托人留学申请的顺利进行。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "9、 受托人应遵守留学快问平台用户协议及相关平台规则，并自行承担因本合同履行产生的相应税费责任。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        
        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', 'B', $fontSize);
        $content = "六、  丙方的权利和义务";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', '', $fontSize);
        $content = "1、 丙方有权要求委托人和受托人提供真实、准确、实时更新的主体信息，委托人和受托人应予以提供。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "2、 丙方有权监督留学咨询服务进程，如受托人履行留学咨询服务合同不符合合同约定或留学快问平台规则，则丙方有权按照平台用户协议约定或平台规则的规定，采取相应措施。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "3、 丙方应妥善保管委托人的押金，并按照本合同约定的规则退还或结算押金。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "4、 丙方应向委托人和受托人提供留学快问平台服务，并提供《服务时间规划》等功能，监督委托人和受托人双方留学咨询服务合同的履行进程。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "5、 丙方应自行承担因本合同履行产生的相应税费责任（如有）。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', 'B', $fontSize);
        $content = "七、  押金规定";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', '', $fontSize);
        $content = "1、 委托人应在本合同签订后三个工作日内缴付出国留学申请服务押金（下称“押金”），共计为人民币  ¥5000  元整。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "2、 委托人可选择以下任一方式付款，并备注委托人的姓名：";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings($leftpadding,0,0,0);
        $content = "（1） 银行账号：";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings($leftpadding*2,0,0,0);
        $content = "户名：广州阿基德米科技有限公司";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "开户行：招商银行广州华强路支行";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "账号：120911961110501";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings($leftpadding,0,0,0);
        $content = "（2） 支付宝账号：pay@liuxuekw.com";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings($leftpadding*2,0,0,0);
        $content = "支付宝户名：广州阿基德米科技有限公司";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings($leftpadding,0,0,0);
        $content = "（3） 微信APP支付";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $content = "3、 委托人必须自行承担在留学申请办理过程中发生的其他第三方费用（如院校申请费、护照工本费、公证费、翻译费、体检费、签证费、银行手续费及汇费、机票款、保险费、院校学费、杂费、接机费、住宿费等）。上述费用由委托人自行向相关第三方机构缴付，缴费标准和缴费方式以相关收费机构对委托人的要求为准。如需受托人代为缴付其他第三方费用，相应的费用可与受托人协商确定。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "4、 在不违反本合同其他约定的情况下，押金将按照以下规则处理：";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings($leftpadding,0,0,0);
        $content = "（1）    如委托人通过留学快问平台提供的申请渠道拿到学校录取通知书（包括有条件录取通知书（Conditional Offer）和正式录取通知书（Unconditional Offer））且成功入读（以最终双方在留学快问平台上确认申请的学校为准，不包含学历课程前的语言课程及本合同项下没有申请的学校），则委托人有权在入学后3个月内提供完整入学相关凭证（具体凭证：学历课程学生证、学历课程收钱学费全额缴纳证明、学历课程BRP卡等）后，向丙方及受托人申请退还全额押金；";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "（2）    如委托人通过留学快问平台提供的申请渠道未获得任何一所学校的录取（以最终双方在留学快问平台上确认申请的学校为准，不包含学历课程前的语言课程及本合同项下没有申请的学校），则有权于申请季当年的9月1日至12月31日期间，提供完整的院校结果，向丙方及受托人申请退还全额押金；";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "（3）    如因受托人不递交申请，导致未能获得任何一所学校的录取，则委托人有权要求退还全额押金；";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $pdf->SetFont('stsongstdlight', 'B', $fontSize);
        $content = "（4）    如委托人通过留学快问平台提供的申请渠道获得学校录取但不入读，则委托人无权要求退还押金，该笔押金将作为受托人的咨询服务费，并在受托人提供相应证据材料给丙方审核通过后，由丙方直接支付给受托人，委托人同意对此处理不持异议；";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "（5）    如因委托人不配合受托人递交申请，导致未能获得任何一所学校的录取，则委托人无权要求退还押金，该笔押金将作为受托人的咨询服务费，并在受托人提供相应证据材料给丙方审核通过后，由丙方之间支付给受托人，委托人同意对此处理不持异议。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->SetFont('stsongstdlight', '', $fontSize);
        $pdf->setCellPaddings(0,0,0,0);
        $content = "5、 押金如符合上述第七条第4款第（1）项情形，委托人申请退还押金，则应按以下退还流程操作：";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings($leftpadding,0,0,0);
        $content = "（1）    委托人应在入学后3个月内申请退还押金，否则将不予退还；";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "（2）    提交凭证——委托人将相关入学凭证，发给丙方；";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "（3）    提交视频——委托人用手机拍摄的15秒以上的入学校园视频（能看清本人、本人校园卡及校名等重要信息），并提交给丙方；";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "（4）    审核凭证——丙方对委托人提交的凭证进行审核；";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "（5）    完成退款——自提交正确的申请材料起3个月内，审核成功后全额退还押金。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $content = "6、  如符合上述第七条第4款第（4）和（5）项情形，受托人应在委托人入学申请季当年的12月31日前提供相应的证据材料给丙方。否则，丙方将直接退还押金给委托人，受托人同意对此处理不持异议。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', 'B', $fontSize);
        $content = "八、  合同延期";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', '', $fontSize);
        $content = "1、 若委托人成功获得学校录取（包含有条件录取），无论是否放弃所获录取，受托人不予提供合同延期服务。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "2、 若委托人未获任何学校录取（包含有条件录取），委托人有权选择本合同可延续至下一年度且无需额外支付押金。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "3、 若委托人放弃本年度申请，本合同可延续至下一年度，委托人无需支付额外的押金。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', 'B', $fontSize);
        $content = "九、  终止条款";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', '', $fontSize);
        $content = "1、 委托人在申请退还押金的截止日期之前未提出退还申请或延期申请，本合同自动终止。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "2、 若因不可抗力致使合同目的不能实现，本合同即告终止，根据不可抗力的影响，部分或者全部免除责任，但法律另有规定的除外。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "3、 若发生本合同约定或法律法规、生效裁判文书确定的合同终止情况，本合同即告终止。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', 'B', $fontSize);
        $content = "十、  违约责任";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', '', $fontSize);
        $content = "1、 委托人与受托人均应严格履行本合同中全部条款，任一方违约应承担相应的违约责任。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "2、 若因委托人失联超过30天并导致本合同无法履行，受托人有权终止本合同且不承担任何违约责任。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "3、 若因受托人失联超过30天并导致本合同无法履行，委托人有权向受托人追究因此产生的违约责任。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "4、 本合同终止后，除法律或本合同另有约定外，违约方仍应向守约方承担相应违约责任。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', 'B', $fontSize);
        $content = "十一、 适用法律及争议解决方法";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', '', $fontSize);
        $content = "1、 本合同的履行、解释及争议解决均适用中华人民共和国大陆地区有关法律。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $pdf->SetFont('stsongstdlight', 'B', $fontSize);
        $content = "2、  三方在履行本合同中如发生争议，应由友好协商的方式解决。如协商不成，任何一方均应向广州互联网法院提起诉讼解决争议。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $content = "十二、 合同的补充、变更、修改";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', '', $fontSize);
        $content = "1、 对本合同的任何补充、变更、修改应采用书面补充协议形式。补充协议在三方签署后与本合同具有同等法律效力。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "2、 本协议任一条款被视为废止、无效或不可执行，该条应视为可分的且并不影响本协议其余条款的有效性及可执行性。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "3、 除本合同上明确规定可以填写的内容外，任何在本合同上的手写内容或修改均对各方没有约束力。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', 'B', $fontSize);
        $content = "十三、 特别条款";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', '', $fontSize);
        $content = "1、 委托人与受托人针对具体服务内容及选校名单作出如下特别约定：";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $pdf->setCellPaddings($leftpadding,0,0,0);
        $pdf->SetTextColor(255, 0, 0);
        $content = empty($params['additional_content']) ? '委托人及受托人双方对本合同均无特别约定' : $params['additional_content'];
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetTextColor();
        $content = "2、 三方对特别条款内容已仔细阅读，同意将特别条款的全部内容作为本合同的条款，并共同遵守履行。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $pdf->SetFont('stsongstdlight', 'B', $fontSize);
        $content = "3、 本合同是三方真实意思的表示，各方均已仔细阅读并完全理解、同意其内容。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $content = "十四、 其他";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $content = "1、 本合同以电子化方式自委托人或其代理人、受托人确认或签署后生效，双方可通过电子化方式自行存档，双方均认可电子化签约的法律效力。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $pdf->SetFont('stsongstdlight', '', $fontSize);
        $content = "2、 本合同正本一式三份，具有同等效力，委托人、受托人、丙方各执一份，本合同电子版、复印件与原件具有同等法律效力。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $content = "（以下无正文）";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->Write($lineHeight, '', '', 0, '', true, 0, false, false, 0);
        $pdf->Write($lineHeight, '', '', 0, '', true, 0, false, false, 0);
        $pdf->Write($lineHeight, '', '', 0, '', true, 0, false, false, 0);
        $content = "甲方（签字）：";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "日期：";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->Write($lineHeight, '', '', 0, '', true, 0, false, false, 0);
        $content = "乙方（签字）：";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "日期：";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->Write($lineHeight, '', '', 0, '', true, 0, false, false, 0);
        $content = "丙方（签字）：";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "授权代表（签字）：";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "日期：";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);


        // //Close and output PDF document
        // return $pdf->Output("{$params['contract_no']}.pdf", $dest);
        
        $files = $dest == 'F' ?  storage_path("app/temp/{$params['contract_no']}.pdf")  : "{$params['contract_no']}.pdf";
        $pdf->Output($files, $dest);

        return "temp/{$params['contract_no']}.pdf";
    }

    /**
     * 升级合同
     *
     * @author Ming 2019-10-27
     *
     * @param  [type] $params [description]
     * @param  string $dest   [description]
     * @return [type]         [description]
     */
    public static function downloadUpgradeFreeAgent($params, $dest = 'D')
    {
        $fontSize = 11;
        $leftpadding = 6;
        $lineHeight = 6;
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        // set header and footer fonts
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        
        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->setPrintHeader(true);
        $pdf->setPrintFooter(true);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);
        $pdf->SetMargins(25, 27, 25);
        
        // set font
        $pdf->SetFont('stsongstdlight', '', 20);

        // add a page
        $pdf->AddPage();

        // if($params['title']) {
        //     $pdf->Write(0, $params['title'], '', 0, 'C', true, 0, false, false, 0);
        //     $pdf->Write(3, "", '', 0, 'L', true, 0, false, false, 0);
        // }   
        // $pdf->MultiCell(0, 0, '留学咨询服务合同', $border=0, $align='C', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=FALSE, $autopadding=true, $maxh=0);
        // 

        $title = $params['type_id'] == 4 ? '零申请费项目升级服务留学咨询服务合同' : '零申请费项目服务留学咨询服务合同';
        $pdf->Write(0, $title, '', 0, 'C', true, 0, false, false, 0);
        $pdf->Write(3, "", '', 0, 'L', true, 0, false, false, 0);
        // $pdf->Write(3, "", '', 0, 'L', true, 0, false, false, 0);
        // 
        $pdf->SetFont('stsongstdlight', '', $fontSize);
        $pdf->Write(0, "编号：" . $params['contract_no'], '', 0, 'L', true, 0, false, false, 0);
        $pdf->Ln();
        $pdf->Write(0, "甲方（委托人）：" . $params['student_name'], '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "身份证/护照号码：" . $params['student_card_no'], '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "联系电话：" . $params['student_mobile'], '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "电子邮箱：" . $params['student_email'], '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "紧急联系人：{$params['emergency_contact']},{$params['emergency_mobile']},", '', 0, 'L', true, 0, false, false, 0);

        if($params['pay_after']) { //是押金后付
            $pdf->Write(0, "支付宝账号：{$params['payment_name']},{$params['payment_account']},", '', 0, 'L', true, 0, false, false, 0);
        }

        $pdf->Ln();
        if ($params['studio_type'] > 0 && $params['studio_id'] > 0) {
            $pdf->Write(0, "乙方 （受托人）：" . $params['studio_company'], '', 0, 'L', true, 0, false, false, 0);
            $pdf->Write(0, "社会统一信用代码：" . $params['business_licence_no'], '', 0, 'L', true, 0, false, false, 0);
            $pdf->Write(0, "项目负责人：" . $params['consultant_name'], '', 0, 'L', true, 0, false, false, 0);
        } elseif ($params['consultant_business_at']) {
            $pdf->Write(0, "乙方 （受托人）：" . $params['consultant_company_name'], '', 0, 'L', true, 0, false, false, 0);
            $pdf->Write(0, "社会统一信用代码：" . $params['consultant_company_business_licence_no'], '', 0, 'L', true, 0, false, false, 0);
            $pdf->Write(0, "项目负责人：" . $params['consultant_name'], '', 0, 'L', true, 0, false, false, 0);
        } else {
            $pdf->Write(0, "乙方 （受托人）：" . $params['consultant_name'], '', 0, 'L', true, 0, false, false, 0);
        }
        $pdf->Write(0, "身份证/护照号码：" . $params['consultant_card_no'], '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "联系电话：" . $params['consultant_mobile'], '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "电子邮箱：" . $params['consultant_email'], '', 0, 'L', true, 0, false, false, 0);

        $pdf->Ln();
        $pdf->Write(0, "丙方（留学快问）：" . $params['platform_company_name'], '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "统一社会信用代码：" . $params['platform_business_no'], '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "联系电话：" . $params['platform_mobile'], '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "电子邮箱：" . $params['platform_email'], '', 0, 'L', true, 0, false, false, 0);

        $pdf->Ln();

        $pdf->SetFont('stsongstdlight', '', $fontSize);
        $content = "\t\t根据《中华人民共和国合同法》及相关法律法规的规定，三方经友好协商一致，达成如下合同，以兹共同遵守：";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $pdf->Write(3, "", '', 0, 'L', true, 0, false, false, 0);

        $pdf->SetFont('stsongstdlight', 'B', $fontSize);
        $content = "一、  服务内容";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', '', $fontSize);
        $content = "1、  受托人通过留学快问平台，为委托人提供申请  ";
        $pdf->Write($lineHeight, $content, '', 0, '', false, 0, false, false, 0);
        $pdf->SetFont('stsongstdlight', 'B', $fontSize);
        $pdf->Write($lineHeight, $params['apply_year'] , '', 0, '', false, 0, false, false, 0);
        $pdf->SetFont('stsongstdlight', '', $fontSize);
        $content = "  申请季年份入学，赴  ";
        $pdf->Write($lineHeight, $content, '', 0, '', false, 0, false, false, 0);
        $pdf->SetFont('stsongstdlight', 'B', $fontSize);
        $pdf->Write($lineHeight, $params['apply_country'] , '', 0, '', false, 0, false, false, 0);
        $pdf->SetFont('stsongstdlight', '', $fontSize);
        $content = "  （国家或地区） 留学，就读  ";
        $pdf->Write($lineHeight, $content, '', 0, '', false, 0, false, false, 0);
        $pdf->SetFont('stsongstdlight', 'B', $fontSize);
        $pdf->Write($lineHeight, $params['apply_degree'] , '', 0, '', false, 0, false, false, 0);
        $pdf->SetFont('stsongstdlight', '', $fontSize);
        $content = "  学位，  ";
        $pdf->Write($lineHeight, $content, '', 0, '', false, 0, false, false, 0);
        $pdf->SetFont('stsongstdlight', 'B', $fontSize);
        $pdf->Write($lineHeight, $params['apply_major'] , '', 0, '', false, 0, false, false, 0);
        $pdf->SetFont('stsongstdlight', '', $fontSize);
        $content = "  （专业方向）或相关专业方向的留学申请咨询服务（可申请院校范围请参考留学快问-院校库，选校名单以最终双方在留学快问平台上确认申请的学校为准。委托人至少申请一所学校，英国不超过5所学校，澳洲、爱尔兰不超过3所学校。如委托人与受托人经协商一致同意申请超过以上数量，则可以超出前述限制，由此产生的额外费用由委托人与受托人另行协商确定）。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "2、  委托人和受托人应使用丙方提供的申请渠道递交学校申请。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "3、  受托人为委托人提供的留学申请咨询服务包含以下环节：";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings($leftpadding,0,0,0);
        $content = "（1）    受托人在全面深入了解委托人的留学申请需求（如专业偏好、排名偏好、地理位置偏好、职业发展倾向等）的前提下，帮助委托人对留学申请目标（如国家、专业、学位等）进行优化。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "（2）    受托人在整体分析委托人申请处境的前提下为委托人制定《服务时间规划》，并在服务过程中随时依据委托人各种主客观因素变化进行实时优化。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "（3）    在委托人既定的留学申请目标下，受托人为委托人设计选校方案，并在服务过程中随时依据委托人各种主客观因素变化进行实时优化。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "（4）    受托人针对委托人既定的目标申请学校，为委托人提供的个性化文书服务，包括1份个人简历（CV）、2份推荐信（RL）、1份个人陈述（PS）的撰写，以及上述文书材料的一次修改。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "（5）    受托人针对委托人既定的目标申请学校，代理委托人向目标院校提出留学申请并提交、补充相关材料，为委托人提供申请手续指导及协助，帮助委托人降低手续出错风险。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "（6）    受托人为委托人提供一次留学签证申请服务，如首次签证被拒，则可以免费再获得一次签证服务。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        if ($params['type_id'] == 4) {
            $pdf->setCellPaddings(0,0,0,0);
            $pdf->SetFont('stsongstdlight', 'B', $fontSize);
            $content = "4、  委托人【选择】受托人提供的升级服务：";
            $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

            $pdf->setCellPaddings($leftpadding,0,0,0);
            $pdf->SetTextColor();
            $content = "（1）    升级服务指受托人委托人提供的个性化文书服务，包括一份个人简历（CV）、2份推荐信（RL）、1份个人陈述（PS）的撰写和修改。受托人应与委托人协商修改文书内容，直至达到委托人的要求。";
            $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
            $content = "（2）    升级服务费用总计为￥4000元（大写：肆仟元整）。委托人同意在签署本协议后三日内按照本协议第七条第2款约定的方式，支付升级服务费用。";
            $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
            // $pdf->SetTextColor(255, 0, 0);
            $content = "（3）    升级服务费用托管：委托人和受托人一致同意将50%的升级服务费（计￥2000元）由丙方预支付给受托人，剩余50%的升级服务费（计￥2000元）托管至丙方平台并按照如下规则结算或退还：";
            $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
            $pdf->SetTextColor();

            $pdf->setCellPaddings($leftpadding*2,0,0,0);
            $pdf->SetFont('stsongstdlight', 'B', $fontSize);
            $content = "①托管金结算与退还：";
            $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
            $content = "符合任一以下情形时，丙方将对托管金进行相应的处置：";
            $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

            $pdf->setCellPaddings($leftpadding*3,0,0,0);
            $content = "1）    当委托人及受托人双方均在留学快问平台系统中确认合同完成后，丙方将会与受托人结算托管金；";
            $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
            $content = "2）    若本合同期限届满或合同约定的退款申请截止日期届满（如合同中没有约定退款申请截止日期，则以合同项下入学年份的9月1日为截止日期），委托人既未在留学快问平台系统内提交退款申请，也未确认合同完成的，则视为委托人默认该合同完成，丙方将会与受托人结算托管金;";
            $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
            $content = "3）    当委托人及受托人在留学快问平台系统中确认退款申请后，丙方将按照双方确认的退款金额结算托管金；";
            $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
            $content = "4）    依据生效的法律文书要求。";
            $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

            $pdf->setCellPaddings($leftpadding*2,0,0,0);
            $content = "②退还责任豁免： 当丙方按照上述约定完成托管金退还后，丙方将自动退出委托人与受托人之间的争议；届时无论委托人与受托人之间是否存在后续争议，均不得向丙方主张任何权利或追究留学快问平台的任何责任。但为促使委托人与受托人之间的争议妥善解决，丙方同意提供必要的配合与协助工作，如提供必要的文件资料等。";
            $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

            $pdf->setCellPaddings($leftpadding,0,0,0);
            $content = "（4） 委托人和受托人同意升级服务费按照如下退款规则退还：";
            $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

            $pdf->setCellPaddings($leftpadding*2,0,0,0);
            $content = "①如在受托人提供任一文书初稿前，委托人要求退款，则扣除升级服务费总额的20%，退还委托人剩余80%；";
            $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
            $content = "②如受托人已完成任一文书初稿，并已提供给委托人后，委托人要求退款，则扣除升级服务费总额的80%，退还委托人剩余20%；";
            $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
            $content = "③如受托人已完成个人陈述（PS）和简历（CV）初稿，并已提供给委托人后，除非符合本条第④项之约定，否则已缴纳的升级服务费用不予退还。";
            $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
            $content = "④委托人未拿到任何院校录取，则升级服务费用全额退还；";
            $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
            $content = "在达到上述任一退款条件时，受托人应在三个工作日内将预支全部/部分的升级服务费转账至丙方平台，由丙方统一对委托人执行退款；否则应当按照每日千分之一的标准，支付违约金。";
            $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        }

        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', 'B', $fontSize);
        $content = "二、  委托人权利";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', '', $fontSize);
        $content = "1、 委托人有权利了解受托人的各项服务进度，并有权利要求受托人按照留学快问平台中的《服务时间规划》在确保服务质量的前提下，按时完成服务。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "2、 委托人有权利要求受托人提供留学申请服务过程中涉及的文件、方案或者相关申请材料。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "3、 委托人对申请学校、申请材料、申请策略有最终决定权；委托人对文件寄送、录取结果选择等所有申请步骤拥有最终执行权。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', 'B', $fontSize);
        $content = "三、  委托人义务";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', '', $fontSize);
        $content = "1、  委托人须配合受托人开展申请服务，并确保在服务的各个阶段与受托人保持通讯畅通。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $pdf->SetFont('stsongstdlight', 'B', $fontSize);
        $content = "2、  委托人须向受托人提供完整真实的必需个人信息，并提供有效的证明文件及申请所需文件。如委托人隐瞒、伪造或提供不完整的资料或信息，由此产生的一切后果由委托人自行承担。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        if ($params['pay_after']) {
            $content = "3、  委托人同意授权受托人处理其留学申请相关事宜，委托人不可自己或授权第三方向同一所学校处理申请事宜，委托人如希望申请选校名单以外的学校，应及时主动告知受托人，得到受托人的同意，也可以授权受托人进行此工作。若委托人蓄意隐瞒申请学校或者录取结果，受托人有权利提前终止本服务合同，并向委托人追缴押金，相关法律责任由委托人承担。";
        } else {
            $content = "3、  委托人同意授权受托人处理其留学申请相关事宜，委托人不可自己或授权第三方向同一所学校处理申请事宜，委托人如希望申请选校名单以外的学校，应及时主动告知受托人，得到受托人的同意，也可以授权受托人进行此工作。若委托人蓄意隐瞒申请学校或者录取结果，受托人有权利提前终止本服务合同，所收取押金不予退还，相关法律责任由委托人承担。";
        }

        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "4、  委托人有义务遵守留学快问平台用户协议及相关平台规则，并在服务过程中通过留学快问平台确认服务完成进度。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $content = "四、  受托人权利";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', '', $fontSize);
        $content = "1、 受托人有权利在对外发布的录取数据中包含经过去标识化、脱敏、脱密处理的委托人申请信息。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "2、 受托人有权利要求委托人及时准确地提供目标申请学校名单及其最终申请结果以确认受托人各项服务进度是否顺利开展。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "3、 受托人有权利对委托人的备考情况、个人专业水平、英文水平进行测试，以了解和保证相关咨询服务进度和计划的准确性。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', 'B', $fontSize);
        $content = "五、  受托人义务";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', '', $fontSize);
        $content = "1、 受托人应当按照中华人民共和国法律、法规和本合同约定，为委托人提供自费出国留学申请咨询服务。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "2、 受托人应当以高度勤勉、审慎的义务对待委托人的委托服务。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "3、 受托人应当以其累积的经验及掌握的信息、材料作出判断，尽最大努力维护委托人利益。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "4、 受托人在收到委托人的咨询时，应当及时回复委托人，及时完成委托事项，并应委托人要求通报工作进程。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "5、 受托人须向委托人如实介绍目标申请国家或地区的院校基本情况、专业设置情况、入学要求等相关留学申请信息，并承诺不做虚假陈述。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "6、 受托人对委托人提供的所有材料，包括个人隐私，均负有保密义务，未经委托人同意，不得向无关的第三方透露。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "7、 受托人应遵照《服务时间规划》提供服务，不得擅自更改、减少服务内容或者拖延服务周期。当需要变更或补充《服务时间规划》时，受托人应征得委托人同意。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "8、 受托人应及时向委托人提供留学申请必需的第三方服务指导（如，公证、银行、ETS等，涉及的第三方费用由委托人自行承担），以保证委托人留学申请的顺利进行。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "9、 受托人应遵守留学快问平台用户协议及相关平台规则，并自行承担因本合同履行产生的相应税费责任。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        
        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', 'B', $fontSize);
        $content = "六、  丙方的权利和义务";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', '', $fontSize);
        $content = "1、 丙方有权要求委托人和受托人提供真实、准确、实时更新的主体信息，委托人和受托人应予以提供。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "2、 丙方有权监督留学咨询服务进程，如受托人履行留学咨询服务合同不符合合同约定或留学快问平台规则，则丙方有权按照平台用户协议约定或平台规则的规定，采取相应措施。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "3、 丙方应妥善保管委托人的押金，并按照本合同约定的规则退还或结算押金。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "4、 丙方应向委托人和受托人提供留学快问平台服务，并提供《服务时间规划》等功能，监督委托人和受托人双方留学咨询服务合同的履行进程。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "5、 丙方应自行承担因本合同履行产生的相应税费责任（如有）。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', 'B', $fontSize);
        $content = "七、  押金规定";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', '', $fontSize);
        $content = "1、 委托人应在本合同签订后三个工作日内缴付出国留学申请服务押金（下称“押金”），共计为人民币  ¥5000  元整。";
        if ($params['pay_after']) {
            $pdf->Write($lineHeight, $content, '', 0, '', false, 0, false, false, 0);
            $pdf->SetFont('stsongstdlight', 'B', $fontSize);
            $content = "由于委托人符合押金后补要求，因此签约时暂不需支付押金，如果出现下述第七条第4款第（4）、（5）项规定，委托人应当支付押金作为受托人的咨询服务费，否则委托人应当按照每日千分之一的标准向受托人承担违约责任。";
            $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
            $pdf->SetFont('stsongstdlight', '', $fontSize);
        } else {
            $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        }
        $content = "2、 委托人可选择以下任一方式付款，并备注委托人的姓名：";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings($leftpadding,0,0,0);
        $content = "（1） 银行账号：";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings($leftpadding*2,0,0,0);
        $content = "户名：广州阿基德米科技有限公司";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "开户行：招商银行广州华强路支行";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "账号：120911961110501";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings($leftpadding,0,0,0);
        $content = "（2） 支付宝账号：pay@liuxuekw.com";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings($leftpadding*2,0,0,0);
        $content = "支付宝户名：广州阿基德米科技有限公司";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings($leftpadding,0,0,0);
        $content = "（3） 微信APP支付";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $content = "3、 委托人必须自行承担在留学申请办理过程中发生的其他第三方费用（如院校申请费、护照工本费、公证费、翻译费、体检费、签证费、银行手续费及汇费、机票款、保险费、院校学费、杂费、接机费、住宿费等）。上述费用由委托人自行向相关第三方机构缴付，缴费标准和缴费方式以相关收费机构对委托人的要求为准。如需受托人代为缴付其他第三方费用，相应的费用可与受托人协商确定。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "4、 在不违反本合同其他约定的情况下，押金将按照以下规则处理：";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings($leftpadding,0,0,0);
        $content = "（1）    如委托人通过留学快问平台提供的申请渠道拿到学校录取（包括有条件录取通知书（Conditional Offer）和正式录取通知书（Unconditional Offer），下同）且成功入读（以最终双方在留学快问平台上确认申请的学校为准，不包含学历课程前的语言课程及本合同项下没有申请的学校），则委托人有权在入学后3个月内提供完整入学相关凭证（具体凭证：学历课程学生证、学历课程收钱学费全额缴纳证明、学历课程BRP卡等）后，向丙方及受托人申请退还全额押金；";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "（2）    如委托人通过留学快问平台提供的申请渠道未获得任何一所学校的录取（以最终双方在留学快问平台上确认申请的学校为准，不包含学历课程前的语言课程及本合同项下没有申请的学校），则有权于申请季当年的9月1日至12月31日期间，提供完整的院校结果，向丙方及受托人申请退还全额押金；";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "（3）    如因受托人不递交申请，导致未能获得任何一所学校的录取，则委托人有权要求退还全额押金；";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $pdf->SetFont('stsongstdlight', 'B', $fontSize);
        $content = "（4）    如委托人通过留学快问平台提供的申请渠道获得学校录取但不入读，则委托人无权要求退还押金，该笔押金将作为受托人的咨询服务费，并在受托人提供相应证据材料给丙方审核通过后，由丙方直接支付给受托人，委托人同意对此处理不持异议；";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "（5）    如因委托人不配合受托人递交申请，导致未能获得任何一所学校的录取，则委托人无权要求退还押金，该笔押金将作为受托人的咨询服务费，并在受托人提供相应证据材料给丙方审核通过后，由丙方之间支付给受托人，委托人同意对此处理不持异议。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->SetFont('stsongstdlight', '', $fontSize);
        $pdf->setCellPaddings(0,0,0,0);
        $content = "5、 押金如符合上述第七条第4款第（1）项情形，委托人申请退还押金，则应按以下退还流程操作：";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings($leftpadding,0,0,0);
        $content = "（1）    委托人应在入学后3个月内申请退还押金，否则将不予退还；";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "（2）    提交凭证——委托人将相关入学凭证，发给丙方；";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "（3）    提交视频——委托人用手机拍摄的15秒以上的入学校园视频（能看清本人、本人校园卡及校名等重要信息），并提交给丙方；";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "（4）    审核凭证——丙方对委托人提交的凭证进行审核；";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "（5）    完成退款——自提交正确的申请材料起3个月内，审核成功后全额退还押金。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        if ($params['pay_after']) {
            $content = "如委托人未曾缴纳押金，则在符合上述第七条第4款第（1）至（3）项情形时，委托人有权不予补缴押金。";
            $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        }

        $pdf->setCellPaddings(0,0,0,0);
        $content = "6、  如符合上述第七条第4款第（4）和（5）项情形，受托人应在委托人入学申请季当年的12月31日前提供相应的证据材料给丙方。否则，丙方将直接退还押金给委托人，受托人同意对此处理不持异议" .($params['pay_after'] ? "；如委托人未曾缴纳押金，则受托人应当按照上述第七条第1款规定支付押金，否则应承担相应违约责任" : ""). "。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', 'B', $fontSize);
        $content = "八、  合同延期";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', '', $fontSize);
        $content = "1、 若委托人成功获得学校录取，无论是否放弃所获录取，受托人不予提供合同延期服务。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "2、 若委托人未成功获任何学校录取，委托人有权选择本合同可延续至下一年度且无需额外支付押金" .($params['pay_after'] ? "或按照本合同第七条约定的条件暂缓支付押金" : ""). "。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "3、 若委托人放弃本年度申请，本合同可延续至下一年度，委托人无需支付额外的押金" .($params['pay_after'] ? "或按照本合同第七条约定的条件暂缓支付押金" : ""). "。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', 'B', $fontSize);
        $content = "九、  终止条款";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', '', $fontSize);
        $content = "1、 委托人在申请退还押金的截止日期之前未提出退还申请或延期申请，" .($params['pay_after'] ? "或在符合第七条第4款（4）、（5）项情形下，委托人按时支付押金后，" : ""). "本合同自动终止。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "2、 若因不可抗力致使合同目的不能实现，本合同即告终止，根据不可抗力的影响，部分或者全部免除责任，但法律另有规定的除外。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "3、 若发生本合同约定或法律法规、生效裁判文书确定的合同终止情况，本合同即告终止。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', 'B', $fontSize);
        $content = "十、  违约责任";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', '', $fontSize);
        $content = "1、 委托人与受托人均应严格履行本合同中全部条款，任一方违约应承担相应的违约责任。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "2、 若因委托人失联超过30天并导致本合同无法履行，受托人有权终止本合同且不承担任何违约责任。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "3、 若因受托人失联超过30天并导致本合同无法履行，委托人有权向受托人追究因此产生的违约责任。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "4、 本合同终止后，除法律或本合同另有约定外，违约方仍应向守约方承担相应违约责任。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', 'B', $fontSize);
        $content = "十一、 适用法律及争议解决方法";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', '', $fontSize);
        $content = "1、 本合同的履行、解释及争议解决均适用中华人民共和国大陆地区有关法律。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $pdf->SetFont('stsongstdlight', 'B', $fontSize);
        $content = "2、  三方在履行本合同中如发生争议，应由友好协商的方式解决。如协商不成，任何一方均应向广州互联网法院提起诉讼解决争议。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $content = "十二、 合同的补充、变更、修改";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', '', $fontSize);
        $content = "1、 对本合同的任何补充、变更、修改应采用书面补充协议形式。补充协议在三方签署后与本合同具有同等法律效力。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "2、 本协议任一条款被视为废止、无效或不可执行，该条应视为可分的且并不影响本协议其余条款的有效性及可执行性。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "3、 除本合同上明确规定可以填写的内容外，任何在本合同上的手写内容或修改均对各方没有约束力。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', 'B', $fontSize);
        $content = "十三、 特别条款";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetFont('stsongstdlight', '', $fontSize);
        $content = "1、 委托人与受托人针对具体服务内容及选校名单作出如下特别约定：";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $pdf->setCellPaddings($leftpadding,0,0,0);
        $pdf->SetTextColor(255, 0, 0);
        $content = empty($params['additional_content']) ? '委托人及受托人双方对本合同均无特别约定' : $params['additional_content'];
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $pdf->setCellPaddings(0,0,0,0);
        $pdf->SetTextColor();
        $content = "2、 三方对特别条款内容已仔细阅读，同意将特别条款的全部内容作为本合同的条款，并共同遵守履行。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $pdf->SetFont('stsongstdlight', 'B', $fontSize);
        $content = "3、 本合同是三方真实意思的表示，各方均已仔细阅读并完全理解、同意其内容。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $content = "十四、 其他";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $content = "1、 本合同以电子化方式自委托人或其代理人、受托人确认或签署后生效，双方可通过电子化方式自行存档，双方均认可电子化签约的法律效力。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $pdf->SetFont('stsongstdlight', '', $fontSize);
        $content = "2、 本合同正本一式三份，具有同等效力，委托人、受托人、丙方各执一份，本合同电子版、复印件与原件具有同等法律效力。";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->setCellPaddings(0,0,0,0);
        $content = "（以下无正文）";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->Write($lineHeight, '', '', 0, '', true, 0, false, false, 0);
        $pdf->Write($lineHeight, '', '', 0, '', true, 0, false, false, 0);
        $pdf->Write($lineHeight, '', '', 0, '', true, 0, false, false, 0);
        $content = "甲方（签字）：";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "日期：";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->Write($lineHeight, '', '', 0, '', true, 0, false, false, 0);
        $content = "乙方（签字）：";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "日期：";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);

        $pdf->Write($lineHeight, '', '', 0, '', true, 0, false, false, 0);
        $content = "丙方（签字）：";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "授权代表（签字）：";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);
        $content = "日期：";
        $pdf->Write($lineHeight, $content, '', 0, '', true, 0, false, false, 0);


        // //Close and output PDF document
        // return $pdf->Output("{$params['contract_no']}.pdf", $dest);
        
        $files = $dest == 'F' ?  storage_path("app/temp/{$params['contract_no']}.pdf")  : "{$params['contract_no']}.pdf";
        $pdf->Output($files, $dest);

        return "temp/{$params['contract_no']}.pdf";
    }
}