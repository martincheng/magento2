<?php
/**
 * Integration edit container.
 *
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @copyright   Copyright (c) 2013 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Magento\Integration\Block\Adminhtml\Integration;

use Magento\Integration\Block\Adminhtml\Integration\Edit\Tab\Info;
use Magento\Integration\Controller\Adminhtml\Integration;

class Edit extends \Magento\Adminhtml\Block\Widget\Form\Container
{
    /**
     * Core registry
     *
     * @var \Magento\Core\Model\Registry
     */
    protected $_registry = null;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Core\Model\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Core\Model\Registry $registry,
        array $data = array()
    ) {
        $this->_registry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Initialize Integration edit page
     *
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_integration';
        $this->_blockGroup = 'Magento_Integration';
        parent::_construct();
        $this->_removeButton('reset');
        $this->_removeButton('delete');

        if ($this->_isNewIntegration()) {
            $this->removeButton('save')->addButton(
                'save',
                [
                    'id' => 'save-split-button',
                    'label' => __('Save'),
                    'class_name' => 'Magento\Backend\Block\Widget\Button\SplitButton',
                    'button_class' => 'PrimarySplitButton',
                    'data_attribute' => [
                        'mage-init' => [
                            'button' => ['event' => 'save', 'target' => '#edit_form'],
                        ],
                    ],
                    'options' => [
                        'save_activate' => [
                            'id' => 'activate',
                            'label' => __('Save & Activate'),
                            'data_attribute' => [
                                'mage-init' => [
                                    'button' => [
                                        'event' => 'saveAndActivate',
                                        'target' => '#edit_form',
                                    ],
                                    'integration' => [
                                        'gridUrl' => $this->getUrl('*/*/'),
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            );
        }
    }

    /**
     * Get header text for edit page.
     *
     * @return string
     */
    public function getHeaderText()
    {
        if ($this->_isNewIntegration()) {
            return __('New Integration');
        } else {
            return __(
                "Edit Integration '%1'",
                $this->escapeHtml(
                    $this->_registry->registry(Integration::REGISTRY_KEY_CURRENT_INTEGRATION)[Info::DATA_NAME]
                )
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFormActionUrl()
    {
        return $this->getUrl('*/*/save');
    }

    /**
     * Determine whether we create new integration or editing an existing one.
     *
     * @return bool
     */
    protected function _isNewIntegration()
    {
        return !isset($this->_registry->registry(Integration::REGISTRY_KEY_CURRENT_INTEGRATION)[Info::DATA_ID]);
    }
}
