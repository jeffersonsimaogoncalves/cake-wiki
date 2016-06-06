<?php
namespace Scherersoftware\Wiki\Controller;

use Cake\Core\Configure;
use Cake\Event\Event;

/**
 * WikiPages Controller
 *
 * @property \App\Model\Table\WikiPagesTable $WikiPages
 */
class WikiPagesController extends AppController
{

    /**
     * Return the ListFilter config for the given action.
     *
     * @param string $action Controller action name
     * @return array
     */
    public function getListFilters($action)
    {
        $filters = [];
        if ($action == 'index') {
            $filters = [
                'fields' => [
                    'WikiPages.fulltext' => [
                        'searchType' => 'fulltext',
                        'searchFields' => [
                            'WikiPages.title',
                            'WikiPages.content',
                        ]
                    ],
                ]
            ];
        }
        return $filters;
    }

    /**
     * Initialize
     *
     * @return void
     */
    public function initialize()
    {
        $this->loadModel('scherersoftware/Wiki.WikiPages');
        parent::initialize();
    }

    /**
     * beforeFilter
     *
     * @param Event $event Event
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        $userId = $this->Auth->user('id');
        if (Configure::read('Wiki.useModelHistory')) {
            $this->WikiPages->setModelHistoryUserIdCallback(function () use ($userId) {
                return $userId;
            });
        }
        parent::beforeFilter($event);
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $conditions = [];
        $searchActive = false;
        if (isset($this->paginate['conditions'])) {
            $searchActive = true;
            $conditions = $this->paginate['conditions'];
        }
        
        $pageTree = $this->WikiPages->find('threaded', [
            'fields' => ['id', 'parent_id', 'title'],
            'order' => ['sort ASC'],
            'conditions' => $conditions
        ])->toArray();

        if (Configure::read('Wiki.useModelHistory')) {
            $recentChanges = $this->WikiPages->getRecentChanges(15);
        }
        $this->set(compact('pageTree', 'recentChanges', 'searchActive'));
    }

    /**
     * View method
     *
     * @param string|null $id Wiki Page id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->WikiPages->recover();
        $wikiPage = $this->WikiPages->get($id, [
            'contain' => ['ParentWikiPages', 'ChildWikiPages', 'Attachments']
        ]);

        $treePath = $this->WikiPages->find('path', [
            'for' => $id
        ])->toArray();
        $this->set('wikiPage', $wikiPage);
        $this->set('treePath', $treePath);
        $pageTree = $this->WikiPages->find('threaded', [
            'fields' => ['id', 'parent_id', 'title'],
            'order' => ['sort ASC']
        ])->hydrate(true)->toArray();
        $this->set(compact('pageTree'));
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $wikiPage = $this->WikiPages->newEntity();
        if ($this->request->is('post')) {
            $wikiPage = $this->WikiPages->patchEntity($wikiPage, $this->request->data);
            if ($this->WikiPages->save($wikiPage)) {
                $this->Flash->success(__('forms.data_saved'));
                return $this->redirect(['action' => 'edit', $wikiPage->id]);
            } else {
                $this->Flash->error(__('forms.data_not_saved'));
            }
        }
        $parentWikiPages = $this->WikiPages->getTreeList();
        $this->set(compact('wikiPage', 'parentWikiPages'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Wiki Page id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $wikiPage = $this->WikiPages->get($id, [
            'contain' => ['Attachments']
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $wikiPage = $this->WikiPages->patchEntity($wikiPage, $this->request->data);
            if ($this->WikiPages->save($wikiPage)) {
                $this->Flash->success(__('forms.data_saved'));
                return $this->redirect(['action' => 'edit', $id]);
            } else {
                $this->Flash->success(__('forms.data_not_saved'));
            }
        }
        $parentWikiPages = $this->WikiPages->getTreeList();
        $this->set(compact('wikiPage', 'parentWikiPages'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Wiki Page id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $wikiPage = $this->WikiPages->get($id);
        $wikiPage->status = 'deleted';
        if ($this->WikiPages->save($wikiPage)) {
            $this->Flash->success(__('forms.data_deleted'));
        } else {
            $this->Flash->error(__('forms.data_not_deleted'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
