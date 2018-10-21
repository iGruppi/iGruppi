pragma solidity ^0.4.24;

import "./ProductManager.sol";
import "./SafeMath.sol";


contract GASContract is ProductManager {
    using SafeMath for uint;

    constructor(string _ownerMetadata) {
        addUser(msg.sender, _ownerMetadata);
    }

    function()
        public
        payable
        onlyUser
    {
        userMapping[msg.sender].userBalance = userMapping[msg.sender].userBalance.add(msg.value);
    }

    function withdraw()
        public
        onlyOwner
    {
        msg.sender.transfer(this.balance);
    }

    function currentBalance()
        public
        constant
        returns(uint)
    {
        return this.balance;
    }
}
