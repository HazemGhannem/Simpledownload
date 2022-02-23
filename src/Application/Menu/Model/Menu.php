<?php



namespace App\Application\Menu\Model;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Menu
{
    /**
     * @var string
     */
    protected $currentRouteName;

    /**
     * @var Collection|Item[]
     */
    protected $items;

    /**
     * Menu constructor.
     *
     * @param string $currentRouteName
     */
    public function __construct(string $currentRouteName)
    {
        $this->items            = new ArrayCollection();
        $this->currentRouteName = $currentRouteName;
    }

    /**
     * @param Item $item
     *
     * @return Menu
     */
    public function addItem(Item $item): self
    {
        $this->items->add($item);
        $item->setMenu($this);

        foreach ($item->getChildren() as $child) {
            $child->setMenu($this);
        }

        return $this;
    }

    /**
     * @return Item[]|Collection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return string
     */
    public function getCurrentRouteName(): string
    {
        return $this->currentRouteName;
    }
}
