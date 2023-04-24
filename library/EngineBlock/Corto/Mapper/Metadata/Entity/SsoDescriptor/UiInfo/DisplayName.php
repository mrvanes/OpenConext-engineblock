<?php

/**
 * Copyright 2010 SURFnet B.V.
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

use OpenConext\EngineBlock\Metadata\Entity\AbstractRole;

class EngineBlock_Corto_Mapper_Metadata_Entity_SsoDescriptor_UiInfo_DisplayName
{
    /**
     * @var AbstractRole
     */
    private $_entity;

    /**
     * @param AbstractRole $entity
     */
    public function __construct(AbstractRole $entity)
    {
        $this->_entity = $entity;
    }

    public function mapTo(array $rootElement)
    {
        $displayNames = [];
        $mdui = $this->_entity->getMdui();
        $availableLanguages = $mdui->getLanguagesByElementName('DisplayName');

        foreach ($availableLanguages as $language) {
            $displayName = $mdui->getDisplayName($language);
            if ($displayName !== '') {
                $displayNames[$language] = $displayName;
            }
        }

        if (empty($displayNames)) {
            return $rootElement;
        }

        if (!isset($rootElement['md:Extensions'])) {
            $rootElement['md:Extensions'] = [];
        }
        if (!isset($rootElement['md:Extensions']['mdui:UIInfo'])) {
            $rootElement['md:Extensions']['mdui:UIInfo'] = [ 0 => []];
        }

        foreach($displayNames as $languageCode => $value) {
            if(empty($value)) {
                continue;
            }

            $rootElement['md:Extensions']['mdui:UIInfo'][0]['mdui:DisplayName'][] = array(
                EngineBlock_Corto_XmlToArray::ATTRIBUTE_PFX . 'xml:lang' => $languageCode,
                EngineBlock_Corto_XmlToArray::VALUE_PFX => $value
            );
        }
        return $rootElement;
    }
}
