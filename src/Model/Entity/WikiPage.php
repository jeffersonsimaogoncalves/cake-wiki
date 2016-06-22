<?php
namespace Scherersoftware\Wiki\Model\Entity;

use Cake\ORM\Entity;
use Cake\Routing\Router;
use Cake\Utility\Inflector;

/**
 * WikiPage Entity.
 */
class WikiPage extends Entity
{

    const ACTIVE = 'active';
    const DELETED = 'deleted';

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'parent_id' => true,
        'lft' => true,
        'rght' => true,
        'sort' => true,
        'title' => true,
        'content' => true,
        'parent_wiki_page' => true,
        'child_wiki_pages' => true,
        'attachments' => true,
        'attachment_uploads' => true
    ];

    /**
     * Getter for URL virtual field
     *
     * @return void
     */
    protected function _getUrl()
    {
        return Router::url([
            '_name' => 'wikiPages',
            'id' => $this->id,
            'slug' => Inflector::slug($this->title)
        ]);
    }
}
