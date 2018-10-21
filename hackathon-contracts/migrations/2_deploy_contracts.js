var GAS = artifacts.require("./GASContract.sol");

module.exports = function(deployer) {
  deployer.deploy(GAS, "owner");
};
