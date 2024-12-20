<?php

/**
 * Copyright 2021 Stichting Kennisnet
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

class EngineBlock_Corto_Filter_Command_SRAMTestFilter extends EngineBlock_Corto_Filter_Command_Abstract
    implements EngineBlock_Corto_Filter_Command_ResponseAttributesModificationInterface
{
    private $_string = null;

    public function __construct($string)
    {
        $this->_string = $string;
        error_log("SRAMTestFilter __construct(" . $this->_string. ")");
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseAttributes()
    {
        return $this->_responseAttributes;
    }

    public function execute(): void
    {
        error_log("SRAMTestFilter execute(" . $this->_string. ")");
        $this->_responseAttributes['eduPersonEntitlement'] = ['aa-foobar', 'bb-foobar'];
        error_log("_responseAttributes: " . var_export($this->_responseAttributes, true));
    }
}
