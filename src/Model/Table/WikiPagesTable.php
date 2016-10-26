<?php
namespace Scherersoftware\Wiki\Model\Table;

use Cake\Core\Configure;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Scherersoftware\Wiki\Model\Entity\WikiPage;

/**
 * WikiPages Model
 */
class WikiPagesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('wiki_pages');
        $this->displayField('title');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
        $this->addBehavior('Tree');
        $this->addBehavior('Attachments.Attachments');
        if (Configure::read('Wiki.useModelHistory')) {
            $this->addBehavior('ModelHistory.Historizable', Configure::read('Wiki.modelHistoryOptions'));
        }
        $this->belongsTo('ParentWikiPages', [
            'className' => 'Scherersoftware/Wiki.WikiPages',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('ChildWikiPages', [
            'className' => 'Scherersoftware/Wiki.WikiPages',
            'foreignKey' => 'parent_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create')
            ->add('parent_id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('parent_id')
            ->add('sort', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('sort')
            ->notEmpty('title')
            ->allowEmpty('content');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['parent_id'], 'ParentWikiPages'));
        return $rules;
    }

    /**
     * Returns an indented tree list for use in a <select>
     *
     * @return array
     */
    public function getTreeList()
    {
        $parentWikiPages = $this->ParentWikiPages->find('treeList', [
            'spacer' => '~',
            'order' => ['title ASC'],
        ])->toArray();
        foreach ($parentWikiPages as $pageId => $title) {
            if (strpos($title, '~') !== false) {
                $pos = strrpos($title, '~');
                $spacers = substr($title, 0, $pos + 1);
                $plainTitle = substr($title, $pos + 1);
                $title = str_replace('~', '&nbsp;&nbsp;', $spacers) . '- ' . $plainTitle;
            } else {
                $title = '- ' . $title;
            }
            $parentWikiPages[$pageId] = $title;
        }
        return $parentWikiPages;
    }

    /**
     * Find a list of recent changes
     *
     * @param string $wikiPageId optional scope on a specific wiki page
     * @param int $limit How many changes to return
     * @return Query
     */
    public function getRecentChanges($wikiPageId = null, $limit = 15)
    {
        $this->ModelHistory->belongsTo($this->registryAlias(), [
            'foreignKey' => 'foreign_key'
        ]);
        $q = $this->ModelHistory->find()
            ->select([
                'ModelHistory.id',
                'ModelHistory.user_id',
                'ModelHistory.action',
                'ModelHistory.created',
                'WikiPages.id',
                'WikiPages.title'
            ])
            ->select($this->ModelHistory->Users)
            ->contain(['Users', 'WikiPages'])
            ->order(['ModelHistory.created DESC'])
            ->where(['WikiPages.status IS NOT' => WikiPage::DELETED])
            ->limit($limit);
        if (!empty($wikiPageId)) {
            $q->where([
                'WikiPages.id' => $wikiPageId,
            ]);
        }
        return $q;
    }
}
