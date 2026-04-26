<?php

namespace App\Enums;

enum OrderPaymentMethod: string
{
    case CreditCard = 'credit_card';
    case DebitCard = 'debit_card';
    case CashOnDelivery = 'cash_on_delivery';
    case DigitalWallet = 'digital_wallet';
    case BankTransfer = 'bank_transfer';
}
