pragma solidity ^0.4.24;

import "./Ownable.sol";


contract UserManager is Ownable {

    struct User {
        address userAddress;
        string userMetadata;
        uint userBalance;
    }

    address[] public usersLUT;
    mapping(address => uint) public userAddressLUTPosition;
    mapping(address => User) public userMapping;

    function userExists(address _user)
        public
        constant
        returns(bool)
    {
        if (userAddressLUTPosition[_user] == 0)
            return false;
        else
            return true;
    }

    function userCount()
        public
        constant
        returns(uint)
    {
        return usersLUT.length;
    }

    function getUsers()
        public
        constant
        returns(address[])
    {
        return usersLUT;
    }

    function getUser(address _userAddress)
        public
        constant
        returns(
            address userAddress,
            string userMetadata,
            uint userBalance
        )
    {
        userAddress = userMapping[_userAddress].userAddress;
        userMetadata = userMapping[_userAddress].userMetadata;
        userBalance = userMapping[_userAddress].userBalance;
    }

    function addUser(address _userAddress, string _userMetadata)
        public
        onlyOwner
    {
        require(
            _userAddress != 0x0 &&
            !userExists(_userAddress)
        );

        userMapping[_userAddress] = User(_userAddress, _userMetadata, 0);
        userAddressLUTPosition[_userAddress] = usersLUT.push(_userAddress);
    }

    function removeUser(address _userAddress)
        public
        onlyOwner
    {
        require(
            userExists(_userAddress) &&
            _userAddress != owner()
        );

        usersLUT[userAddressLUTPosition[_userAddress] - 1] = usersLUT[userCount() - 1];
        userAddressLUTPosition[usersLUT[userCount() - 1]] = userAddressLUTPosition[_userAddress];
        userAddressLUTPosition[_userAddress] = 0;
        usersLUT.length--;
        userMapping[_userAddress] = User(0x0, "", 0);
    }

    modifier onlyUser() {
        require(userExists(msg.sender));
        _;
    }
}
