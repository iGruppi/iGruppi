pragma solidity ^0.4.0;

import "./UserManager.sol";


contract ProductManager is UserManager {

    event ProductAdded(uint indexed _productId);

    struct Action {
        uint productId;
        address actionCreator;
        string actionMetadata;
        uint actionTimestamp;
        mapping(address => mapping(uint8 => uint8)) actionVotes;
    }

    struct Product {
        uint productId;
        uint productActionsLength;
        mapping(uint => Action) productActions;
    }

    string[] voteCategories;
    mapping(address => Action[]) userActions;
    Product[] products;

    function addVoteCategory(string _categoryName)
        public
        onlyOwner
    {
        voteCategories.push(_categoryName);
    }

    function getVoteCategory(uint _categoryId)
        public
        constant
        returns(string)
    {
        require(_categoryId < voteCategories.length);
        return voteCategories[_categoryId];
    }

    function getVoteCategoriesCount()
        public
        constant
        returns(uint)
    {
        return voteCategories.length;
    }

    function addProductAction(uint _productId, string _actionMetadata)
        public
        onlyUser
    {
        require(_productId < products.length);
        uint actionIndex = products[_productId].productActionsLength;
        products[_productId].productActions[actionIndex] = Action(_productId, msg.sender, _actionMetadata, now);
        products[_productId].productActionsLength++;
        userActions[msg.sender].push(Action(_productId, msg.sender, _actionMetadata, now));
    }

    function getProductCount()
        public
        constant
        returns(uint)
    {
        return products.length;
    }

    function getProductActionCount(uint _productId)
        public
        constant
        returns(uint)
    {
        require(_productId < getProductCount());
        return products[_productId].productActionsLength;
    }

    function getProductAction(uint _productId, uint _actionIndex)
        public
        constant
        returns(
            uint productId,
            address actionCreator,
            string actionMetadata,
            uint actionTimestamp
        )
    {
        require(_productId < getProductCount());
        productId = products[_productId].productActions[_actionIndex].productId;
        actionCreator = products[_productId].productActions[_actionIndex].actionCreator;
        actionMetadata = products[_productId].productActions[_actionIndex].actionMetadata;
        actionTimestamp = products[_productId].productActions[_actionIndex].actionTimestamp;
    }

    function addProduct(string _actionMetadata)
        public
        onlyUser
    {
        products.push(Product(products.length, 0));
        addProductAction(products.length - 1, _actionMetadata);

        emit ProductAdded(getProductCount() - 1);
    }

    function getUserActionCount(address _userAddress)
        public
        constant
        returns(uint)
    {
        return userActions[_userAddress].length;
    }

    function getUserAction(address _userAddress, uint _actionIndex)
        public
        constant
        returns(
            uint productId,
            address actionCreator,
            string actionMetadata,
            uint actionTimestamp
        )
    {
        require(_actionIndex < userActions[_userAddress].length);
        productId = userActions[_userAddress][_actionIndex].productId;
        actionCreator = userActions[_userAddress][_actionIndex].actionCreator;
        actionMetadata = userActions[_userAddress][_actionIndex].actionMetadata;
        actionTimestamp = userActions[_userAddress][_actionIndex].actionTimestamp;
    }

    function addActionVote(uint _productId, uint _actionId, uint8 _voteCategory, uint8 _value)
        public
        onlyUser
    {
        require(
            _value < 5 &&
            _voteCategory < voteCategories.length
        );
        products[_productId].productActions[_actionId].actionVotes[msg.sender][_voteCategory] = _value;
    }

    function getActionVote(uint _productId, uint _actionId, uint8 _voteCategory, address _userAddress)
        public
        constant
        returns(uint8)
    {
        return products[_productId].productActions[_actionId].actionVotes[_userAddress][_voteCategory];
    }
}
