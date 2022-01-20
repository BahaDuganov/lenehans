<?php
/**
 * Created by Magenest JSC.
 * Author: Jacob
 * Date: 10/01/2019
 * Time: 9:41
 */

namespace Magenest\StripePayment\Block\Info\Przelewy;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Registry;

class Info extends \Magento\Framework\View\Element\Template
{
    protected $_template = "Magenest_StripePayment::info/payment.phtml";

    protected $coreRegistry;

    public function __construct(
        Registry $registry,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->coreRegistry = $registry;
    }

    public function getOrder()
    {
        return $this->coreRegistry->registry("current_order");
    }

    public function getDataDisplay()
    {
        $order = $this->getOrder();
        $data = [];
        if ($order) {
            $payment = $order->getPayment();
            if ($payment) {
                $referenceCode = $payment->getAdditionalInformation("stripe_przelewy_reference");
                $data[] = [
                    'label' => "Reference Code",
                    'value' => $referenceCode
                ];
            }
        }

        return $data;
    }
}
