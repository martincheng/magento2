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
 * @category    Magento
 * @package     Magento_Adminhtml
 * @copyright   Copyright (c) 2013 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Block for Catalog Category URL rewrites editing
 *
 * @method \Magento\Catalog\Model\Category getCategory()
 * @method
 * \Magento\Adminhtml\Block\Urlrewrite\Catalog\Product\Edit setCategory(\Magento\Catalog\Model\Category $category)
 * @method \Magento\Catalog\Model\Product getProduct()
 * @method \Magento\Adminhtml\Block\Urlrewrite\Catalog\Product\Edit setProduct(\Magento\Catalog\Model\Product $product)
 * @method bool getIsCategoryMode()
 *
 * @category   Magento
 * @package    Magento_Adminhtml
 * @author     Magento Core Team <core@magentocommerce.com>
 */
namespace Magento\Adminhtml\Block\Urlrewrite\Catalog\Product;

class Edit extends \Magento\Adminhtml\Block\Urlrewrite\Edit
{
    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;

    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $_categoryFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Core\Model\Url\RewriteFactory $rewriteFactory
     * @param \Magento\Backend\Helper\Data $adminhtmlData
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Catalog\Model\CategoryFactory $categoryFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Core\Model\Url\RewriteFactory $rewriteFactory,
        \Magento\Backend\Helper\Data $adminhtmlData,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        array $data = array()
    ) {
        $this->_categoryFactory = $categoryFactory;
        $this->_productFactory = $productFactory;
        parent::__construct($context, $rewriteFactory, $adminhtmlData, $data);
    }

    /**
     * Prepare layout for URL rewrite creating for product
     */
    protected function _prepareLayoutFeatures()
    {
        if ($this->_getUrlRewrite()->getId()) {
            $this->_headerText = __('Edit URL Rewrite for a Product');
        } else {
            $this->_headerText = __('Add URL Rewrite for a Product');
        }

        if ($this->_getProduct()->getId()) {
            $this->_addProductLinkBlock($this->_getProduct());
        }

        if ($this->_getCategory()->getId()) {
            $this->_addCategoryLinkBlock();
        }

        if ($this->_getProduct()->getId()) {
            if ($this->_getCategory()->getId() || !$this->getIsCategoryMode()) {
                $this->_addEditFormBlock();
                $this->_updateBackButtonLink(
                    $this->_adminhtmlData
                        ->getUrl('adminhtml/*/edit', array('product' => $this->_getProduct()->getId())) . 'category'
                );
            } else {
                // categories selector & skip categories button
                $this->_addCategoriesTreeBlock();
                $this->_addSkipCategoriesBlock();
                $this->_updateBackButtonLink($this->_adminhtmlData->getUrl('adminhtml/*/edit') . 'product');
            }
        } else {
            $this->_addUrlRewriteSelectorBlock();
            $this->_addProductsGridBlock();
        }
    }

    /**
     * Get or create new instance of product
     *
     * @return \Magento\Catalog\Model\Product
     */
    private function _getProduct()
    {
        if (!$this->hasData('product')) {
            $this->setProduct($this->_productFactory->create());
        }
        return $this->getProduct();
    }

    /**
     * Get or create new instance of category
     *
     * @return \Magento\Catalog\Model\Category
     */
    private function _getCategory()
    {
        if (!$this->hasData('category')) {
            $this->setCategory($this->_categoryFactory->create());
        }
        return $this->getCategory();
    }

    /**
     * Add child product link block
     */
    private function _addProductLinkBlock()
    {
        $this->addChild('product_link', 'Magento\Adminhtml\Block\Urlrewrite\Link', array(
            'item_url'  => $this->_adminhtmlData->getUrl('adminhtml/*/*') . 'product',
            'item_name' => $this->_getProduct()->getName(),
            'label'     => __('Product:')
        ));
    }

    /**
     * Add child category link block
     */
    private function _addCategoryLinkBlock()
    {
        $this->addChild('category_link', 'Magento\Adminhtml\Block\Urlrewrite\Link', array(
            'item_url'  => $this->_adminhtmlData
                ->getUrl('adminhtml/*/*', array('product' => $this->_getProduct()->getId())) . 'category',
            'item_name' => $this->_getCategory()->getName(),
            'label'     => __('Category:')
        ));
    }

    /**
     * Add child products grid block
     */
    private function _addProductsGridBlock()
    {
        $this->addChild('products_grid', 'Magento\Adminhtml\Block\Urlrewrite\Catalog\Product\Grid');
    }

    /**
     * Add child Categories Tree block
     */
    private function _addCategoriesTreeBlock()
    {
        $this->addChild('categories_tree', 'Magento\Adminhtml\Block\Urlrewrite\Catalog\Category\Tree');
    }

    /**
     * Add child Skip Categories block
     */
    private function _addSkipCategoriesBlock()
    {
        $this->addChild('skip_categories', 'Magento\Adminhtml\Block\Widget\Button', array(
            'label' => __('Skip Category Selection'),
            'onclick' => 'window.location = \''
                . $this->_adminhtmlData->getUrl('adminhtml/*/*', array('product' => $this->_getProduct()->getId()))
                . '\'',
            'class' => 'save',
            'level' => -1
        ));
    }

    /**
     * Creates edit form block
     *
     * @return \Magento\Adminhtml\Block\Urlrewrite\Catalog\Edit\Form
     */
    protected function _createEditFormBlock()
    {
        return $this->getLayout()->createBlock('Magento\Adminhtml\Block\Urlrewrite\Catalog\Edit\Form', '', array(
            'data' => array(
                'product'     => $this->_getProduct(),
                'category'    => $this->_getCategory(),
                'url_rewrite' => $this->_getUrlRewrite()
            )
        ));
    }
}
