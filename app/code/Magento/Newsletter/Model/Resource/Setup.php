<?php
/**
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

/**
 * Newsletter resource setup
 */
namespace Magento\Newsletter\Model\Resource;

class Setup extends \Magento\Core\Model\Resource\Setup
{
    /**
     * Resource setup model
     *
     * @var \Magento\Core\Model\Resource\Setup\Migration
     */
    protected $_setupMigration;

    /**
     * @param \Magento\Core\Model\Resource\Setup\Context $context
     * @param string $resourceName
     * @param string $moduleName
     * @param string $connectionName
     */
    public function __construct(
        \Magento\Core\Model\Resource\Setup\Context $context,
        $resourceName,
        $moduleName = 'Magento_Newsletter',
        $connectionName = ''
    ) {
        parent::__construct($context, $resourceName, $moduleName, $connectionName);
        $this->_setupMigration = $this->_migrationFactory->create(
            array('resourceName' => 'core_setup')
        );
    }

    /**
     * Get block factory
     *
     * @return \Magento\Core\Model\Resource\Setup\Migration
     */
    public function getSetupMigration()
    {
        return $this->_migrationFactory->create(array('resourceName' => 'core_setup'));
    }
}
