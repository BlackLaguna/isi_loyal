<?php

namespace ClientPurchases\Domain;

interface LoyaltyProgramClientRepository
{
    public function findByClientAndLoyaltyProgram(Client $client, LoyaltyProgram $loyaltyProgram): LoyaltyProgramClient;
}