<?php

/**
 * This file is part of the Symfony2-coding-standard (phpcs standard)
 *
 * PHP version 5
 *
 * @category PHP
 * @package  Symfony2-coding-standard
 * @author   Authors <Symfony2-coding-standard@djoos.github.com>
 * @license  http://spdx.org/licenses/MIT MIT License
 * @link     https://github.com/djoos/Symfony2-coding-standard
 */

namespace Symfony\Sniffs\Objects;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * ObjectInstantiationSniff.
 *
 * Throws a warning if an object isn't instantiated using parenthesis.
 *
 * PHP version 5
 *
 * @category PHP
 * @package  Symfony2-coding-standard
 * @author   Authors <Symfony2-coding-standard@djoos.github.com>
 * @license  http://spdx.org/licenses/MIT MIT License
 * @link     https://github.com/djoos/Symfony2-coding-standard
 */
class ObjectInstantiationSniff implements Sniff
{
    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = array(
                                   'PHP',
                                  );


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
                T_NEW,
               );
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token
     *                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $allowed = array(
            T_STRING,
            T_NS_SEPARATOR,
            T_VARIABLE,
            T_STATIC,
            T_SELF,
        );

        $object = $stackPtr;
        $line   = $tokens[$object]['line'];

        while ($object && $tokens[$object]['line'] === $line) {
            $object = $phpcsFile->findNext($allowed, $object + 1);

            if ($tokens[$object]['line'] === $line
                && !in_array($tokens[$object + 1]['code'], $allowed)
            ) {
                if ($tokens[$object + 1]['code'] !== T_OPEN_PARENTHESIS) {
                    $phpcsFile->addError(
                        'Use parentheses when instantiating classes',
                        $stackPtr,
                        'Invalid'
                    );
                }

                break;
            }
        }
    }
}
