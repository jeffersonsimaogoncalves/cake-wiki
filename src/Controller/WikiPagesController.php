<?php
namespace Scherersoftware\Wiki\Controller;

use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Utility\Hash;
use Scherersoftware\Wiki\Model\Entity\WikiPage;

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
        $conditions = [
            'status' => WikiPage::ACTIVE
        ];
        $searchActive = false;
        if (isset($this->paginate['conditions'])) {
            $searchActive = true;
            $conditions = Hash::merge($this->paginate['conditions'], $conditions);
        }

        $pageTree = $this->WikiPages->find('threaded', [
            'fields' => ['id', 'parent_id', 'title'],
            'order' => ['title ASC'],
            'conditions' => $conditions
        ])->toArray();

        if (Configure::read('Wiki.useModelHistory')) {
            $recentChanges = $this->WikiPages->getRecentChanges();
        }
        $this->set(compact('pageTree', 'recentChanges', 'searchActive'));
    }

    /**
     * View method
     *
     * @param string|null $id Wiki Page id.
     * @return mixed
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->WikiPages->recover();
        $wikiPage = $this->WikiPages->get($id, [
            'contain' => [
                'ParentWikiPages', 'ChildWikiPages', 'Attachments'
            ]
        ]);

        if ($wikiPage->status == WikiPage::DELETED) {
            $this->Flash->warning(__d('wiki', 'wiki_pages.page_does_not_exist'));

            return $this->redirect(['action' => 'index']);
        }

        $treePath = $this->WikiPages->find('path', [
            'for' => $id
        ])->toArray();
        $this->set('wikiPage', $wikiPage);
        $this->set('treePath', $treePath);
        $pageTree = $this->WikiPages->find('threaded', [
            'fields' => ['id', 'parent_id', 'title'],
            'order' => ['title ASC'],
            'conditions' => [
                'status' => WikiPage::ACTIVE
            ]
        ])->hydrate(true)->toArray();

        if (Configure::read('Wiki.useModelHistory')) {
            $recentChanges = $this->WikiPages->getRecentChanges($id);
        }
        $this->set(compact('pageTree', 'recentChanges'));
    }

    /**
     * Add method
     *
     * @return mixed Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $wikiPage = $this->WikiPages->newEntity();
        if ($this->request->is('post')) {
            $this->request->data['status'] = WikiPage::ACTIVE;
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
     * @return mixed Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $wikiPage = $this->WikiPages->get($id, [
            'contain' => ['Attachments']
        ]);

        if ($wikiPage->status == WikiPage::DELETED) {
            $this->Flash->warning(__d('wiki', 'wiki_pages.page_does_not_exist'));

            return $this->redirect(['action' => 'index']);
        }

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
     * @return redirect Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $wikiPage = $this->WikiPages->get($id);
        $wikiPage->status = WikiPage::DELETED;
        if ($this->WikiPages->save($wikiPage)) {
            $this->Flash->success(__('forms.data_deleted'));
        } else {
            $this->Flash->error(__('forms.data_not_deleted'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
