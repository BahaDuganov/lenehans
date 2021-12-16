<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/module-sorting
 * @version   1.1.14
 * @copyright Copyright (C) 2021 Mirasvit (https://mirasvit.com/)
 */


declare(strict_types=1);

namespace Mirasvit\Sorting\Controller\Track;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Mirasvit\Core\Service\SerializeService;
use Mirasvit\Sorting\Repository\CriterionPopularityRepository;

class CriterionPopularity extends Action
{
    private $criterionPopularityRepository;

    public function __construct(
        CriterionPopularityRepository $criterionPopularityRepository,
        Context                       $context
    ) {
        $this->criterionPopularityRepository = $criterionPopularityRepository;

        parent::__construct($context);
    }

    public function execute(): void
    {
        $criterionCode = (string)$this->getRequest()->getParam('criterion');

        $this->criterionPopularityRepository->incrementPopularity($criterionCode);

        /** @var \Magento\Framework\App\Response\Http $response */
        $response = $this->getResponse();
        $response->representJson(SerializeService::encode([
            'success' => true,
        ]));
    }
}